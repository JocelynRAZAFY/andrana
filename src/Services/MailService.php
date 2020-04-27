<?php
/**
 * Created by PhpStorm.
 * User: dave
 * Date: 11/03/19
 * Time: 15:19
 */

namespace App\Services;

use App\Email\IMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpKernel\KernelInterface;

class MailService
{
    private static function trimQuotes(string $s): string {
        return trim($s, " '\\\"\t\n\r\0\x0B");
    }

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var LoggerInterface
     */
    protected $logger;


    /**
     * MailService constructor.
     * @param \Swift_Mailer $mailer
     * @param KernelInterface $kernel
     * @param ContainerInterface $container
     * @param LoggerInterface $logger

     */
    public function __construct(
        \Swift_Mailer $mailer,
        KernelInterface $kernel,
        ContainerInterface $container,
        LoggerInterface $logger
    ) {
        $this->mailer = $mailer;
        $this->kernel = $kernel;
        $this->container = $container;
        $this->logger = $logger;

    }

    /**
     * Send basic email
     *
     * @param string $recipient
     * @param string $subject
     * @param string $body
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendBasicMail(string $recipient, string $subject, string $body, string $mime = 'text/plain')
    {
        $mailUser = $this->envDotService->mailUser;
        $vars = [
            'username' => ''
        ];
        $message = new \Swift_Message($subject);
        $html = $this->container->get('twig')->render("email/beefree.html.twig",$vars, true);

        $message
            ->setFrom($mailUser, $mailUser)
            ->setTo($recipient)
//            ->setBody($body, $mime)
            ->setBody($html, 'text/html')
            ->setCharset('UTF-8');

        $this->mailer->send($message);
    }

    /**
     * Send email for user notification
     *
     * @param string $recipient
     * @param string $first_name
     * @param string $link_url
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */

    public function sendMail(string $to, IMessage $message,$isMe)
    {
        $email_message = (new \Swift_Message($message->getSubject()));

        $vars = ($message->getVariables()) ? $message->getVariables() : [];
        $vars['isMe'] = $isMe;
        foreach ((($message->getImages()) ? $message->getImages() : []) as $key => $image) {
//            if (is_string($image)) {
//                $image = ['path' => $image];
//            }

            $file_image = $this->kernel->getProjectDir().$image['path'];

            $img = \Swift_Image::fromPath($file_image);
            $img->setDisposition('inline');
            $img->setFilename('');
            if (isset($image['type']) && $image['type']) {
                $img->setContentType($image['type']);
            }

            $vars[$key] = $email_message->embed($img);
        }

        $mailUser = $this->container->getParameter('mailer_user');
        $template = $message->getTemplate();
        $html = $this->container->get('twig')->render("email/${template}.html.twig",$vars, true);

        $email_message
            ->setFrom($mailUser, $mailUser)
            ->setTo($to)
            ->setBody($html, 'text/html')
            ->setCharset('UTF-8');

        $this->mailer->send($email_message);
    }

    /**
     * @param string $recipient
     * @param string $name
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendTest(string $recipient, string $name)
    {
        $contact_mail = $this->container->getParameter('contact_mail');
        $contact_name = self::trimQuotes($this->container->getParameter('contact_name'));

        $message = new \Swift_Message('Test d\'envoi de mail');
        $html = $this->container->get('twig')->render("emails/test.html.twig",[ 'name' => $name ], true);

        $message
            ->setFrom($contact_mail, $contact_name)
            ->setTo($recipient)
            ->setBody($html, 'text/html')
            ->setCharset('UTF-8');

        $this->mailer->send($message);
    }
}