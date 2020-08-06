<?php

namespace App\Controller;

use App\Entity\EatingHouseUser;
use App\Entity\EatingHouses;
use App\Entity\Users;
use maxh\Nominatim\Nominatim;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;


class Admin extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function index() {

        $session_user = $this->session->get('user', 0);

        if(!empty($session_user)) {

            $data["eating_house_id"] = 0;

            $eating_house_id = $this->session->get('eating_house_id', 0);
            //echo $eating_house_id;

            if($eating_house_id == 0) {
                $eating_house_data = [];
                return $this->render('admin/pages/main.html.twig', ['user' => $this->session->get('user'), 'eating_house' => $eating_house_data]);
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

        } else {
            return $this->render('admin/pages/index.html.twig');
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

    public function logout()
    {
        $session_user = $this->session->get('user', 0);

        if(!empty($session_user)) {

            $this->session->invalidate();
            return $this->redirectToRoute('admin_index');
        }
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
                    ->street($post["address"])
                    ->country('Hungary')
                    ->city('Budapest')
                    ->postalCode($post["zip"])
                    ->addressDetails();

                $result = $nominatim->find($search);

                if(strpos($post["website"], "http") === false) {
                    $post["website"] = "http://" . $post["website"];
                }

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

    public function menu() {

    }

    public function signup() {

        $post = json_decode(file_get_contents('php://input'), true);

        if(!empty($post)) {
            //print_R($post);

            $password = trim($post["password"]);
            $email = filter_var(trim($post["email"]), FILTER_VALIDATE_EMAIL);

            if(!empty($email)) {

                $users_check = $this->getDoctrine()
                    ->getRepository(Users::class)
                    ->findOneBy([
                        "email" => $email
                    ]);

                if(empty($users_check)) {

                    if($password == trim($post["password_again"])) {

                        $password = password_hash($post["password"], PASSWORD_BCRYPT);

                        $new_user = new Users();
                        $new_user->setUsername(trim($post["username"]));
                        $new_user->setEmail($email);
                        $new_user->setPassword($password);
                        $new_user->setCreatedAt(new \DateTime());
                        $this->getDoctrine()->getManager()->persist($new_user);
                        $this->getDoctrine()->getManager()->flush();

                        $this->session->set('user', $new_user);
                        $this->session->set('eating_house_id', $new_user->getId());

                        $data["success"] = 1;
                        $data["message"] = "Sikeres regisztráció!";
                        $data["class"] = "success";
                        $data["title"] = "Siker";

                    } else {

                        $data["success"] = 0;
                        $data["message"] = "Sikertelen regisztráció, a jelszavak nem egyeznek!";
                        $data["class"] = "warning";
                        $data["title"] = "Hiba";
                    }
                } else {

                    $data["success"] = 0;
                    $data["message"] = "Sikertelen regisztráció, ez az email-cím már regisztrálva van!";
                    $data["class"] = "warning";
                    $data["title"] = "Hiba";
                }


            } else {

                $data["success"] = 0;
                $data["message"] = "Sikertelen regisztráció, az email-cím érvénytelen!";
                $data["class"] = "warning";
                $data["title"] = "Hiba";
            }



        } else {

            $data["success"] = 0;
            $data["message"] = "Sikertelen regisztráció!";
            $data["class"] = "warning";
            $data["title"] = "Hiba";
        }

        return new JsonResponse($data);
    }

    public function addNewEatinghouse()
    {

        $post = json_decode(file_get_contents('php://input'), true);
        $session_user = $this->session->get('user', 0);

        if(!empty($post)) {


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
                    ->street($post["address"])
                    ->country('Hungary')
                    ->city('Budapest')
                    ->postalCode($post["zip"])
                    ->addressDetails();

                $result = $nominatim->find($search);

                if (strpos($post["website"], "http") === false) {
                    $post["website"] = "http://" . $post["website"];
                }

                $eating_house = new EatingHouses();
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
                $eating_house->setCreatedAt(new \DateTime());

                $this->getDoctrine()->getManager()->persist($eating_house);
                $this->getDoctrine()->getManager()->flush();

                $new_user_eh = new EatingHouseUser();
                $new_user_eh->setEatingHouseId($eating_house->getId());
                $new_user_eh->setUserId($session_user->getId());
                $new_user_eh->setCreatedAt(new \DateTime());

                $this->getDoctrine()->getManager()->persist($new_user_eh);
                $this->getDoctrine()->getManager()->flush();

                $data["success"] = 1;
                $data["message"] = "Sikeres rögzítés!";
                $data["class"] = "success";
                $data["title"] = "Siker!";
                $data["info"] = $field_error;
            }



            return new JsonResponse($data);

        } else {

            $eating_house_data = [
                "id" => "",
                "name" => "",
                "phone" => "",
                "contact" => "",
                "website" => "",
                "zip" => "",
                "district" => "",
                "address" => "",
                "introduction" => ""
            ];

            $user = [
              "username" => $session_user->getUsername()
            ];
        }


        return $this->render('admin/pages/eating_house.html.twig', ['data' => $eating_house_data, 'user' => $user]);
    }
}