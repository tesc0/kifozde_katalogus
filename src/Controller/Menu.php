<?php

namespace App\Controller;

use App\Entity\EatingHouseUser;
use App\Entity\EatingHouses;
use App\Entity\Users;
use App\Entity\Menus;
use maxh\Nominatim\Nominatim;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;



class Menu extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function index() {


        $data["eating_house_id"] = 0;

        $eating_house_id = $this->session->get('eating_house_id', 0);
        //echo $eating_house_id;

        if($eating_house_id == 0) {
            return $this->render('admin/pages/index.html.twig');
        } else {

            $eating_house = $this->getDoctrine()
                ->getRepository(EatingHouses::class)
                ->find($eating_house_id);

            $eating_house_data = [
                "id" => $eating_house->getId(),
                "name" => $eating_house->getName(),
                "phone" => $eating_house->getPhone(),
                "contact" => $eating_house->getContact(),
                "website" => $eating_house->getWebsite(),
                "zip" => $eating_house->getZip(),
                "district" => $eating_house->getDistrict(),
                "address" => $eating_house->getAddress(),
                "introduction" => $eating_house->getIntroduction(),
                "longitude" => $eating_house->getLongitude(),
                "latitude" => $eating_house->getLatitude()
            ];

            return $this->render('admin/pages/main.html.twig', ['user' => $this->session->get('user'), 'eating_house' => $eating_house_data]);
        }


    }

    public function login() {

        $post = json_decode(file_get_contents('php://input'), true);
        //print_R($post);

        $data = [];

        $email = trim($post["email"]);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        if(!empty($email)) {

            $user = $this->getDoctrine()
                ->getRepository(Users::class)
                ->findOneBy([
                    "email" => trim($post["email"])
                ]);


            if(!empty($user)) {
                if( password_verify ( $post["password"], $user->getPassword() )) {

                    $this->session->set('eating_house_id', $user->getId());
                    $this->session->set('user', $user);

                    $data["success"] = 1;
                    $data["message"] = "Sikeres bejelentkezés!";
                    $data["class"] = "success";
                    $data["title"] = "Siker";
                } else {
                    $data["success"] = 0;
                    $data["message"] = "Hibás jelszó!";
                    $data["class"] = "warning";
                    $data["title"] = "Hiba";
                }
            }

        } else {

            $data["success"] = 0;
            $data["message"] = "Érvénytelen email cím!";
            $data["class"] = "warning";
            $data["title"] = "Hiba";
        }

        return new JsonResponse($data);
    }

    public function eatingHouse() {

        $session_user = $this->session->get('user', 0);

        $eating_house_user = $this->getDoctrine()
            ->getRepository(EatingHouseUser::class)
            ->findOneBy([
                "userId" => $session_user->getId()
            ]);

        $eating_house = $this->getDoctrine()
            ->getRepository(EatingHouses::class)
            ->find($eating_house_user->getId());

        $post = json_decode(file_get_contents('php://input'), true);

        if(!empty($post)) {
            //print_R($post);

            foreach($post as $key => $input) {
                if($key != "introduction" && $key != "website" && strlen($input) == 0) {
                    $field_error[] = $key;
                }
                $post[$key] = trim($input);
            }

            if($post["district"] < 1 || $post["district"] > 23) {

            }

            if(!empty($field_error)) {

                $data["success"] = 0;
                $data["message"] = "Hibás/érvénytelen mezők!";
                $data["class"] = "warning";
                $data["title"] = "Hiba";
                $data["info"] = $field_error;

            } else {

                $url = "http://nominatim.openstreetmap.org/";
                $nominatim = new Nominatim($url);

                $search = $nominatim->newSearch()
                    ->country('Hungary')
                    ->city('Budapest')
                    ->postalCode($post["zip"])
                    ->addressDetails($post["address"]);

                $result = $nominatim->find($search);

                $eating_house->setName($post["name"]);
                $eating_house->setContact($post["contact"]);
                $eating_house->setPhone($post["phone"]);
                $eating_house->setZip($post["zip"]);
                $eating_house->setDistrict($post["district"]);
                $eating_house->setAddress($post["address"]);
                $eating_house->setWebsite($post["website"]);
                $eating_house->setIntroduction($post["introduction"]);
                $eating_house->setLongitude($result[0]["lon"]);
                $eating_house->setLatitude($result[0]["lat"]);

                $this->getDoctrine()->getManager()->flush();

                $data["success"] = 1;
                $data["message"] = "Sikeres mentés!";
                $data["class"] = "success";
                $data["title"] = "Siker";
            }

            return new JsonResponse($data);
        }

        $eating_house_data = [
            "id" => $eating_house->getId(),
            "name" => $eating_house->getName(),
            "phone" => $eating_house->getPhone(),
            "contact" => $eating_house->getContact(),
            "website" => $eating_house->getWebsite(),
            "zip" => $eating_house->getZip(),
            "district" => $eating_house->getDistrict(),
            "address" => $eating_house->getAddress(),
            "introduction" => $eating_house->getIntroduction()
        ];

        return $this->render('admin/pages/eating_house.html.twig', ['data' => $eating_house_data]);
    }

    public function listMenu() {

        $eating_house_id = $this->session->get('eating_house_id', 0);
        //echo $eating_house_id;

        if($eating_house_id == 0) {
            return $this->render('admin/pages/index.html.twig');

        } else {

            $date = new \DateTime();
            $week = $date->format("W");
            $day = $date->format("N");

            $monday = date("Y-m-d", strtotime("last monday"));
            $friday = date("Y-m-d", strtotime("this friday"));

            if($day == 6 || $day == 7) {
                $week++;

                $monday = strtotime("next monday");

                if($day == 6) {
                    $friday = date("Y-m-d", strtotime("-1 day"));
                } else {
                    $friday = date("Y-m-d", strtotime("-2 day"));
                }
            }


            $menu_qb = $this->getDoctrine()
                ->getRepository(Menus::class)
                ->createQueryBuilder('m')
                ->where('m.week = :week AND m.eatingHouseId = :eatingHouseId')
                ->setParameters(['week' => $week, 'eatingHouseId' => $eating_house_id])
                ->addOrderBy('m.date', 'ASC')
                ->addOrderBy('m.type', 'ASC');

            $query = $menu_qb->getQuery();
            $menu = $query->execute();

            //print_R($menu);

            $menu_array = [];

            foreach($menu as $item) {
                $date = $item->getDate()->format("Y-m-d");

                //print_R($date);
                $menu_array[$date][$item->getType()] = $item->getItem();
            }


            $period = new \DatePeriod(
                new \DateTime($monday),
                new \DateInterval('P1D'),
                new \DateTime($friday . " 23:59")
            );

            //print_R($period);

            $result = [];
            foreach ($period as $key => $value) {

                $result[$value->format('Y-m-d')] = ['dessert' => '', 'soup' => '', 'main_dish' => ''];

               if(!empty($menu_array[$value->format('Y-m-d')])) {
                   if(!empty($menu_array[$value->format('Y-m-d')][1])) {
                       $result[$value->format('Y-m-d')]['soup'] = $menu_array[$value->format('Y-m-d')][1];
                   }
                   if(!empty($menu_array[$value->format('Y-m-d')][2])) {
                       $result[$value->format('Y-m-d')]['main_dish'] = $menu_array[$value->format('Y-m-d')][2];
                   }
                   if(!empty($menu_array[$value->format('Y-m-d')][3])) {
                       $result[$value->format('Y-m-d')]['dessert'] = $menu_array[$value->format('Y-m-d')][3];
                   }
               }

            }

        }


        return $this->render('admin/pages/menu.html.twig', ['data' => $result, 'week' => $week]);
    }

    public function saveMenu()
    {

        $eating_house_id = $this->session->get('eating_house_id', 0);

        if($eating_house_id == 0) {
            return $this->render('admin/pages/index.html.twig');

        } else {

            $post = json_decode(file_get_contents('php://input'), true);

            if(!empty($post)) {

                $_datetime = new \DateTime($post["soups"][0]["date"]);
                $_week = $_datetime->format("W");

                if(!empty($post["soups"])) {

                    foreach($post["soups"] as $index => $item) {

                        $new_menu = new Menus();
                        $new_menu->setEatingHouseId($eating_house_id);
                        $new_menu->setType(1);
                        $new_menu->setItem($item["name"]);
                        $new_menu->setDate(\DateTime::createFromFormat('Y-m-d', $item["date"]));
                        $new_menu->setWeek($_week);
                        $new_menu->setCreatedAt(new \DateTime());
                        $this->getDoctrine()->getManager()->persist($new_menu);
                        $this->getDoctrine()->getManager()->flush();
                    }

                }

                if(!empty($post["maindishes"])) {

                    foreach($post["maindishes"] as $index => $item) {
                        $new_menu = new Menus();
                        $new_menu->setEatingHouseId($eating_house_id);
                        $new_menu->setType(2);
                        $new_menu->setItem($item["name"]);
                        $new_menu->setDate(\DateTime::createFromFormat('Y-m-d', $item["date"]));
                        $new_menu->setWeek($_week);
                        $new_menu->setCreatedAt(new \DateTime());
                        $this->getDoctrine()->getManager()->persist($new_menu);
                        $this->getDoctrine()->getManager()->flush();
                    }

                }

                if(!empty($post["desserts"])) {

                    foreach($post["desserts"] as $index => $item) {
                        $new_menu = new Menus();
                        $new_menu->setEatingHouseId($eating_house_id);
                        $new_menu->setType(3);
                        $new_menu->setItem($item["name"]);
                        $new_menu->setDate(\DateTime::createFromFormat('Y-m-d', $item["date"]));
                        $new_menu->setWeek($_week);
                        $new_menu->setCreatedAt(new \DateTime());
                        $this->getDoctrine()->getManager()->persist($new_menu);
                        $this->getDoctrine()->getManager()->flush();
                    }

                }


                $data["success"] = 1;
                $data["message"] = "Sikeres mentés!";
                $data["class"] = "success";
                $data["title"] = "Siker";

                return new JsonResponse($data);
            }

            $date = new \DateTime();
            $week = $date->format("W");
            $day = $date->format("N");

            $monday = date("Y-m-d", strtotime("last monday"));
            $friday = date("Y-m-d", strtotime("this friday"));

            if($day == 6 || $day == 7) {
                $week++;

                $monday = strtotime("next monday");

                if($day == 6) {
                    $friday = date("Y-m-d", strtotime("-1 day"));
                } else {
                    $friday = date("Y-m-d", strtotime("-2 day"));
                }
            }

            $period = new \DatePeriod(
                new \DateTime($monday),
                new \DateInterval('P1D'),
                new \DateTime($friday . " 23:59")
            );

            //print_R($period);

            $result = [];
            foreach ($period as $key => $value) {
                $result[] = $value->format("Y-m-d");
            }

            return $this->render('admin/pages/new_menu.html.twig', ['dates' => $result, 'week' => $week]);
        }
    }

    public function changeMenu(string $date) {

        $eating_house_id = $this->session->get('eating_house_id', 0);

        if($eating_house_id == 0) {
            return $this->render('admin/pages/index.html.twig');

        } else {

            $post = json_decode(file_get_contents('php://input'), true);

            if(!empty($post)) {

                $menu_day_house_qb = $this->getDoctrine()
                    ->getRepository(Menus::class)
                    ->createQueryBuilder('m')
                    ->where('m.date = :date AND m.eatingHouseId = :eatingHouseId')
                    ->setParameters(['date' => $date, 'eatingHouseId' => $eating_house_id]);

                $query = $menu_day_house_qb->getQuery();
                $menu_day_house = $query->execute();

                if(!empty($menu_day_house)) {
                    foreach($menu_day_house as $menu_item) {
                        $menuitemId = $menu_item->getId();
                        $menuitemType = $menu_item->getType();

                        $menu__update = $this->getDoctrine()
                            ->getRepository(Menus::class)
                            ->find($menuitemId);

                        if($menuitemType == 1) {
                            $menu__update->setItem($post["soup"]);
                        }
                        if($menuitemType == 2) {
                            $menu__update->setItem($post["main_dish"]);
                        }
                        if($menuitemType == 3) {
                            $menu__update->setItem($post["dessert"]);
                        }

                        $this->getDoctrine()->getManager()->flush();

                    }
                } else {

                    $now = new \DateTime();
                    $week = $now->format("W");
                    $day = $now->format("N");

                    if($day == 6 || $day == 7) {
                        $week++;
                    }

                    if(!empty($post["soup"])) {
                        $new_menu = new Menus();
                        $new_menu->setEatingHouseId($eating_house_id);
                        $new_menu->setType(1);
                        $new_menu->setItem($post["soup"]);
                        $new_menu->setDate(\DateTime::createFromFormat('Y-m-d', $date));
                        $new_menu->setWeek($week);
                        $new_menu->setCreatedAt(new \DateTime());
                        $this->getDoctrine()->getManager()->persist($new_menu);
                        $this->getDoctrine()->getManager()->flush();
                    }

                    if(!empty($post["main_dish"])) {
                        $new_menu = new Menus();
                        $new_menu->setEatingHouseId($eating_house_id);
                        $new_menu->setType(2);
                        $new_menu->setItem($post["main_dish"]);
                        $new_menu->setDate(\DateTime::createFromFormat('Y-m-d', $date));
                        $new_menu->setWeek($week);
                        $new_menu->setCreatedAt(new \DateTime());
                        $this->getDoctrine()->getManager()->persist($new_menu);
                        $this->getDoctrine()->getManager()->flush();
                    }

                    if(!empty($post["dessert"])) {
                        $new_menu = new Menus();
                        $new_menu->setEatingHouseId($eating_house_id);
                        $new_menu->setType(3);
                        $new_menu->setItem($post["dessert"]);
                        $new_menu->setDate(\DateTime::createFromFormat('Y-m-d', $date));
                        $new_menu->setWeek($week);
                        $new_menu->setCreatedAt(new \DateTime());
                        $this->getDoctrine()->getManager()->persist($new_menu);
                        $this->getDoctrine()->getManager()->flush();
                    }


                }

                $data["success"] = 1;
                $data["message"] = "Sikeres mentés!";
                $data["class"] = "success";
                $data["title"] = "Siker";

                return new JsonResponse($data);
            }

            $menu_qb = $this->getDoctrine()
                ->getRepository(Menus::class)
                ->createQueryBuilder('m')
                ->where('m.date = :date AND m.eatingHouseId = :eatingHouseId')
                ->setParameters(['date' => $date, 'eatingHouseId' => $eating_house_id])
                ->addOrderBy('m.date', 'ASC')
                ->addOrderBy('m.type', 'ASC');

            $query = $menu_qb->getQuery();
            $menu = $query->execute();

            //print_R($menu);

            $menu_array = [];
            $menu_array[1] = "";
            $menu_array[2] = "";
            $menu_array[3] = "";

            if(!empty($menu)) {
                foreach ($menu as $item) {
                    $menu_array[$item->getType()] = $item->getItem();
                }
            }



            return $this->render('admin/pages/day_menu.html.twig', ['data' => $menu_array, 'date' => $date]);
        }
    }
}