<?php

namespace App\Controller;

use App\Entity\EatingHouseUser;
use App\Entity\EatingHouses;
use App\Entity\Users;
use App\Entity\Menus;
use App\Entity\Subscribers;
use App\Entity\Subscriptions;
use maxh\Nominatim\Nominatim;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;



class Site extends AbstractController
{
    public function index()
    {
        $eating_houses = $this->getDoctrine()
            ->getRepository(EatingHouses::class)
            ->findAll();

        //print_R($eating_houses);

        $data = [];

        $now = new \DateTime();
        $week = $now->format("W");
        $day = $now->format("N");

        $monday = date("Y-m-d", strtotime("last monday"));
        $friday = date("Y-m-d", strtotime("this friday"));

        if($day == 6) {
            $friday = date("Y-m-d", strtotime("-1 day"));
        } else if($day == 7) {
            $friday = date("Y-m-d", strtotime("-2 day"));
        }

        //echo $monday . "---" . $friday;

        if(!empty($eating_houses)) {
            foreach($eating_houses as $house) {

                $menu_qb = $this->getDoctrine()
                    ->getRepository(Menus::class)
                    ->createQueryBuilder('m')
                    ->where('m.week = :week AND m.eatingHouseId = :eatingHouseId')
                    ->setParameters(['week' => $week, 'eatingHouseId' => $house->getId()])
                    ->addOrderBy('m.date', 'ASC')
                    ->addOrderBy('m.type', 'ASC');

                $query = $menu_qb->getQuery();
                $menu = $query->execute();

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

                $menu_result = [];
                foreach ($period as $key => $value) {

                    $menu_result[$value->format('Y-m-d')] = ['dessert' => '', 'soup' => '', 'main_dish' => ''];

                    if(!empty($menu_array[$value->format('Y-m-d')])) {
                        if(!empty($menu_array[$value->format('Y-m-d')][1])) {
                            $menu_result[$value->format('Y-m-d')]['soup'] = $menu_array[$value->format('Y-m-d')][1];
                        }
                        if(!empty($menu_array[$value->format('Y-m-d')][2])) {
                            $menu_result[$value->format('Y-m-d')]['main_dish'] = $menu_array[$value->format('Y-m-d')][2];
                        }
                        if(!empty($menu_array[$value->format('Y-m-d')][3])) {
                            $menu_result[$value->format('Y-m-d')]['dessert'] = $menu_array[$value->format('Y-m-d')][3];
                        }
                    }

                }


                $data[] = [
                    "id" => $house->getId(),
                    "name" => $house->getName(),
                    "phone" => $house->getPhone(),
                    "website" => $house->getWebsite(),
                    "zip" => $house->getZip(),
                    "district" => $house->getDistrict(),
                    "address" => $house->getAddress(),
                    "longitude" => $house->getLongitude(),
                    "latitude" => $house->getLatitude(),
                    "introduction" => $house->getIntroduction(),
                    "menu" => $menu_result
                ];
            }
        }

        return $this->render('main/pages/index.html.twig', ['eating_houses' => $data]);
    }

    public function search()
    {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = [
            "list" => [],
            "success" => 0
        ];

        $now = new \DateTime();
        $week = $now->format("W");
        $day = $now->format("N");

        $monday = date("Y-m-d", strtotime("last monday"));
        $friday = date("Y-m-d", strtotime("this friday"));

        if($day == 6) {
            $friday = date("Y-m-d", strtotime("-1 day"));
        } else if($day == 7) {
            $friday = date("Y-m-d", strtotime("-2 day"));
        }

        if(!empty($post)) {

            $name = trim($post["name"]);
            $district = trim($post["district"]);

            $eating_houses_qb = $this->getDoctrine()
                ->getRepository(EatingHouses::class)
                ->createQueryBuilder('eh');

            if(!empty($name) && !empty($district)) {
                $eating_houses_qb
                    ->where("eh.name LIKE :name AND eh.district = :district")
                    ->setParameters(['name' => '%' . $name . '%', 'district' => $district]);
            } else if(!empty($name)) {
                $eating_houses_qb
                    ->where("eh.name LIKE :name")
                    ->setParameter('name', '%' . $name . '%');
            } else if(!empty($district)) {
                $eating_houses_qb
                    ->where("eh.district = :district")
                    ->setParameter('district', $district);
            }

            $query = $eating_houses_qb->getQuery();
            $eating_houses = $query->execute();

            //print_R($eating_houses);

            $result["success"] = 1;


            if(!empty($eating_houses)) {
                foreach($eating_houses as $house) {

                    $menu_qb = $this->getDoctrine()
                        ->getRepository(Menus::class)
                        ->createQueryBuilder('m')
                        ->where('m.week = :week AND m.eatingHouseId = :eatingHouseId')
                        ->setParameters(['week' => $week, 'eatingHouseId' => $house->getId()])
                        ->addOrderBy('m.date', 'ASC')
                        ->addOrderBy('m.type', 'ASC');

                    $query = $menu_qb->getQuery();
                    $menu = $query->execute();

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

                    $menu_result = [];
                    foreach ($period as $key => $value) {

                        $menu_result[$value->format('Y-m-d')] = ['dessert' => '', 'soup' => '', 'main_dish' => ''];

                        if(!empty($menu_array[$value->format('Y-m-d')])) {
                            if(!empty($menu_array[$value->format('Y-m-d')][1])) {
                                $menu_result[$value->format('Y-m-d')]['soup'] = $menu_array[$value->format('Y-m-d')][1];
                            }
                            if(!empty($menu_array[$value->format('Y-m-d')][2])) {
                                $menu_result[$value->format('Y-m-d')]['main_dish'] = $menu_array[$value->format('Y-m-d')][2];
                            }
                            if(!empty($menu_array[$value->format('Y-m-d')][3])) {
                                $menu_result[$value->format('Y-m-d')]['dessert'] = $menu_array[$value->format('Y-m-d')][3];
                            }
                        }

                    }

                    $data[] = [
                        "id" => $house->getId(),
                        "name" => $house->getName(),
                        "phone" => $house->getPhone(),
                        "website" => $house->getWebsite(),
                        //"zip" => $house->getZip(),
                        //"district" => $house->getDistrict(),
                        "address" => $house->getAddress(),
                        //"longitude" => $house->getLongitude(),
                        //"latitude" => $house->getLatitude(),
                        "introduction" => $house->getIntroduction(),
                        "menu" => $menu_result
                    ];
                }

                $result["list"] = $data;
            }
        }

        return new JsonResponse($result);
    }

    public function subscribe()
    {
        $post = json_decode(file_get_contents('php://input'), true);

        $result = [
            "success" => 0
        ];

        if(!empty($post)) {

            $email = filter_var($post["email"], FILTER_VALIDATE_EMAIL);

            if(!empty($post["subscribed_houses"]) && !empty($email)) {

                $subscriber = new Subscribers();
                $subscriber->setEmail($email);
                $subscriber->setCreatedAt(new \DateTime());
                $this->getDoctrine()->getManager()->persist($subscriber);
                $this->getDoctrine()->getManager()->flush();

                foreach($post["subscribed_houses"] as $subbed_id) {
                    $subscription = new Subscriptions();
                    $subscription->setEatingHouseId($subbed_id);
                    $subscription->setSubscriberId($subscriber->getId());
                    $subscription->setCreatedAt(new \DateTime());
                    $this->getDoctrine()->getManager()->persist($subscription);
                    $this->getDoctrine()->getManager()->flush();
                }

                $result["success"] = 1;
            }
        }

        return new JsonResponse($result);
    }
}