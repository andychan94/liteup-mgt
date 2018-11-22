<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 8/31/2018
 * Time: 2:46 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\VerificationCondition;
use AppBundle\Entity\VerifyRequest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class VerificationController extends Controller
{
    /**
     * @Route("/verification-step-one", name="verification_step_one")
     */
    public function verificationStepOneAction(Request $request)
    {
        return $this->render('dashboard/verification/verify-index.html.twig');
    }

    /**
     * @Route("/verification-step-two", name="verification_step_two")
     */
    public function verificationStepTwoAction(Request $request)
    {
        return $this->render('dashboard/verification/what-is-verify.html.twig');
    }

    /**
     * @Route("/verification-step-three", name="verification_step_three")
     */
    public function verificationStepthreeAction(Request $request)
    {
        return $this->render('dashboard/verification/type-verify.html.twig');
    }

    /**
     * @Route("/individual-verification", name="individual_verification")
     */
    public function individualVerificationAction(Request $request)
    {
        $verificationConditions = $this->getDoctrine()->getRepository(VerificationCondition::class)->findBy(
            ['isTop' => true],
            ['verificationConditionOrder' => 'ASC']);
        $verificationType = $this->getDoctrine()->getRepository('AppBundle:VerificationType')->find(2);

        return $this->render('dashboard/verification/individual-verify.html.twig',
            array(
                'verifyConditions' => $verificationConditions,
                'verificationType' => $verificationType,

            ));
    }

    /**
     * @Route("/corporate-verification", name="corporate_verification")
     */
    public function corporateVerificationAction(Request $request)
    {
        $verificationType = $this->getDoctrine()->getRepository('AppBundle:VerificationType')->find(1);

        return $this->render('dashboard/verification/corporate-verify.html.twig',
            array(
                'verificationType' => $verificationType,
            ));
    }

    /**
     * @Route("/verify-confirm-request", name="verify_confirm_request")
     */
    public function verifyRequestAction(Request $request, \Swift_Mailer $mailer)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $condition = $request->get('verifyCondition');
        $file = $request->files->get('document');
        $verifyCondition = null;
        if ($condition) {
            $verifyCondition = $em->getRepository('AppBundle:VerificationCondition')->find($condition);
        } else {
            $condition = null;
        }

        if (!empty($file)) {

            $fileName = 'verify' . md5(uniqid()) . '.' . $file->guessExtension();

            if (!file_exists($this->getParameter('verify_docs'))) {
                mkdir("/uploads/verify_docs", 0755, true);
            }
            $file->move($this->getParameter('verify_docs'), $fileName);
            $verifyRequest = new VerifyRequest();

            $verifyRequest->setAgency($this->getUser());
            $verifyRequest->setVerifyCondition($verifyCondition);
            $verifyRequest->setVerifyDocs($fileName);
            $em->persist($verifyRequest);
            $em->flush();
            $this->addFlash('success', 'Congratulations your
                            verification request has been completed!!! Our agents will confirm your details and verify your accounts
                            so you can enjoy the full benefits of a verified account');

            $verificationMail = $em->getRepository('AppBundle:Email')->find(2);

            $message = (new \Swift_Message($verificationMail->getEmailSubject()))
                ->setFrom($this->getParameter('mailer_user'))
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'Emails/verification-notification.html.twig',
                        array(
                            'name' => $user->getName(),
                            'text' => $verificationMail->getEmailText()
                        )
                    ),
                    'text/html'
                );

            $mailer->send($message);


            return $this->redirectToRoute('dashboard_home');

        }
        return $this->redirectToRoute('verification_step_one');
    }


    /**
     * @Route("/regulation-account", name="regulation_account")
     */
    public function regulationAccountAction()
    {

        return $this->render('dashboard/agency/delete-account.html.twig');
    }

    /**
     * @Route("/delete-account", name="delete_account")
     */
    public function deleteAccountAction(Request $request)
    {
        $delete_type = $request->get('delete_type');
        $em = $this->getDoctrine()->getManager();
        $agency = $em->getRepository('AppBundle:Agency');
        $user = $agency->find($this->getUser());
        if ($delete_type == 2) {
            $house = $em->getRepository('AppBundle:House')->findBy(['agency' => $user]);
            if ($house) {
                foreach ($house as $key) {
                    if ($key->getPhotos()) {
                        foreach ($key->getPhotos() as $photos) {
                            $photo = $this->getParameter('remove_uploads_folder') . $photos->getPath();
                            if (file_exists($photo)) {
                                unlink($photo);
                            }
                        }
                    }
                }
            }
            $logo = $this->getParameter('agency_logo_folder') . $user->getLogo();

            if ($user->getVerifyRequest()) {

                $verify_docs = $this->getParameter('remove_verify_docs') . $user->getVerifyRequest()->getVerifyDocs();
                if (file_exists($verify_docs)) {
                    unlink($verify_docs);
                }
            }

            if (file_exists($logo)) {
                unlink($logo);
            }
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'Your account deleted');
            return $this->redirectToRoute('fos_user_registration_register');

        } elseif ($delete_type == 1) {

            $user->setDeactivate(true);
            $user->setDeactivateAt(new \DateTime());
            $em->flush();
            $this->addFlash('success', 'Your account disabled');

            return $this->redirectToRoute('fos_user_security_logout');
        }
        return $this->redirectToRoute('homepage');

    }

    /**
     * @Route("/reactivate-request", name="reactivate_request")
     */
    public function userRestoreAction()
    {
        return $this->render('dashboard/agency/delete-account-restore.html.twig');
    }

    /**
     * @Route("/reactivation-confirmation/{id}", name="reactivation_confirmation")
     */
    public function reactivationConfirmationAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Agency');
        $user = $repository->find($id);

        if ($user) {
            if ($user->getUsername() == $this->getUser()->getUsername()) {

                $user->setDeactivate(false);
                $em->flush();
                $this->addFlash('success', 'Your account successfully reactivated');
                return $this->redirectToRoute('dashboard_home');
            }
        }
        return $this->redirectToRoute('homepage');
    }
}