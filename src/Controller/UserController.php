<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="api_home")
     */
    public function home()
    {
        return $this->json(['result' => true]);
    }

    /**
     * @Route("/register", name="api_register", methods={"POST"})
     */
    public function register(ObjectManager $om, UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {
        $user = new User();

        $email                  = $request->request->get("email");
        $password               = $request->request->get("password");
        $passwordConfirmation   = $request->request->get("password_confirmation");

        //Array holds errors
        $errors = [];

        //Check if password does not match the confirmation
        if($password != $passwordConfirmation)
        {
            $errors[] = "Password does not match the password confirmation.";
        }
        //check that the password is at least 6 characters long
        if(strlen($password) < 6)
        {
            $errors[] = "Password should be at least 6 characters.";
        }

        if(!$errors)
        {
            //Encrypt the password
            $encodedPassword = $passwordEncoder->encodePassword($user, $password);

            //Setter methods to set the email and password properties of the user
            $user->setEmail($email);
            $user->setPassword($encodedPassword);

            try
            {
                //Save new user
                $om->persist($user);
                $om->flush();

                //return the User object as json
                return $this->json([
                    'user' => $this->getUser()
                ],
                    200,
                    [],
                    [
                        'groups' => ['api']
                    ]
                );
            }
            catch(UniqueConstraintViolationException $e)
            {
                $errors[] = "The email provided already has an account!";
            }
            catch(\Exception $e)
            {
                $errors[] = "Unable to save new user at this time.";
            }
        }

        //return the $errors as json if there are any with status code
        return $this->json([
            'errors' => $errors
        ], 400);
    }

    /**
     * @Route("/login", name="api_login", methods={"POST"})
     */
    public function login()
    {
        return $this->json(['result' => true]);
    }

    /**
     * @Route("/profile", name="api_profile")
     * @IsGranted("ROLE_USER")
     */
    public function profile()
    {
        return $this->json([
            'user' => $this->getUser()
        ],
            200,
            [],
            [
                'groups' => ['api']
            ]
        );
    }
}
