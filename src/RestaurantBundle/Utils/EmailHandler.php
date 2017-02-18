<?php

namespace RestaurantBundle\Utils;

use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\Templating\EngineInterface;

class EmailHandler {

    private $mailer;
    private $templating;
    private $logger;
    private $em;
    private $config;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, EntityManager $em, LoggerInterface $logger, array $config) {
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->logger = $logger;
        $this->em = $em;
        $this->config = $config;
    }

    /**
     * @param $menu
     * @return $this
     */
    public function sendMenuValidationRequest($menu) {

        $usersToNotify = $this->em->getRepository('RestaurantBundle:Utilisateur')->findBy(
            array('role' => array('ROLE_REVIEWER','ROLE_CHEF'))
        );

        foreach ($usersToNotify as $user) {

            try {
                $message = \Swift_Message::newInstance()
                    ->setSubject($this->config['menu_validation_request']['subject'])
                    ->setFrom($this->config['email_address'])
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->templating->render(
                            $this->config['menu_validation_request']['template'],
                            array('menu' => $menu)
                        ),
                        'text/html'
                    );

                $this->mailer->send($message);
            }
            catch(\Swift_TransportException $e) {
                $this->logger->error($e->getMessage());
            }

        }

        return $this;
    }

    /**
     * @param $menu
     * @return $this
     */
    public function sendPlatValidationRequest($plat) {

        $usersToNotify = $this->em->getRepository('RestaurantBundle:Utilisateur')->findBy(
            array('role' => array('ROLE_REVIEWER','ROLE_CHEF'))
        );

        foreach ($usersToNotify as $user) {

            try {
                $message = \Swift_Message::newInstance()
                    ->setSubject($this->config['plat_validation_request']['subject'])
                    ->setFrom($this->config['email_address'])
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->templating->render(
                            $this->config['plat_validation_request']['template'],
                            array('plat' => $plat)
                        ),
                        'text/html'
                    );

                $this->mailer->send($message);
            }
            catch(\Swift_TransportException $e) {
                $this->logger->error($e->getMessage());
            }

        }

        return $this;
    }

    /**
     * @param $menu
     * @return $this
     */
    public function notifyPlatMissingInMenu($menu) {

        $usersToNotify = $this->em->getRepository('RestaurantBundle:Utilisateur')->findBy(
            array('role' => array('ROLE_REVIEWER','ROLE_CHEF'))
        );

        foreach ($usersToNotify as $user) {

            try {
                $message = \Swift_Message::newInstance()
                    ->setSubject($this->config['plat_missing_notification']['subject'])
                    ->setFrom($this->config['email_address'])
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->templating->render(
                            $this->config['plat_missing_notification']['template'],
                            array('menu' => $menu)
                        ),
                        'text/html'
                    );

                $this->mailer->send($message);
            }
            catch(\Swift_TransportException $e) {
                $this->logger->error($e->getMessage());
            }

        }

        return $this;
    }

    /**
     * @param string $email
     * @return Response
     */
    public function sendBookingConfirmation($reservation) {

        try {
            $message = \Swift_Message::newInstance()
                ->setSubject($this->config['booking_confirmation']['subject'])
                ->setFrom($this->config['email_address'])
                ->setTo($reservation->getEmail())
                ->setBody(
                    $this->templating->render(
                        $this->config['booking_confirmation']['template'],
                        array('reservation' => $reservation)
                    ),
                    'text/html'
                );

            $this->mailer->send($message);
        }
        catch(\Swift_TransportException $e) {
            $this->logger->error($e->getMessage());
        }

        return $this;
    }

    /**
     * @param $menu
     * @return $this
     */
    public function notifyMenuPublication($menu) {

        $usersToNotify = $this->em->getRepository('RestaurantBundle:Utilisateur')->findBy(
            array('role' => 'ROLE_SERVEUR')
        );

        foreach ($usersToNotify as $user) {

            try {
                $message = \Swift_Message::newInstance()
                    ->setSubject($this->config['menu_publication_notification']['subject'])
                    ->setFrom($this->config['email_address'])
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->templating->render(
                            $this->config['menu_publication_notification']['template'],
                            array('menu' => $menu)
                        ),
                        'text/html'
                    );

                $this->mailer->send($message);
            }
            catch(\Swift_TransportException $e) {
                $this->logger->error($e->getMessage());
            }

        }

        return $this;
    }

    /**
     * @param $plat
     * @return $this
     */
    public function notifyPlatPublication($plat) {

        $usersToNotify = $this->em->getRepository('RestaurantBundle:Utilisateur')->findBy(
            array('role' => 'ROLE_SERVEUR')
        );

        foreach ($usersToNotify as $user) {

            try {
                $message = \Swift_Message::newInstance()
                    ->setSubject($this->config['plat_publication_notification']['subject'])
                    ->setFrom($this->config['email_address'])
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->templating->render(
                            $this->config['plat_publication_notification']['template'],
                            array('plat' => $plat)
                        ),
                        'text/html'
                    );

                $this->mailer->send($message);
            }
            catch(\Swift_TransportException $e) {
                $this->logger->error($e->getMessage());
            }

        }

        return $this;
    }
}