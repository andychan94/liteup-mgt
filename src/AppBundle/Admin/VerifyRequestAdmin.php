<?php

namespace AppBundle\Admin;

use AppBundle\Entity\VerifyRequest;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;


class VerifyRequestAdmin extends AbstractAdmin
{
    public function toString($object)
    {
        return $object instanceof VerifyRequest
            ? $object->getId()
            : 'User';
    }

    protected function configureFormFields(FormMapper $form)
    {
        $form->add('isVerified');
    }

    protected function configureListFields(ListMapper $list)
    {


        $list
            ->add('isVerified', null, ['editable' => true])
            ->add('agency.name', null, array(
                'template' => "SonataAdminBundle:CRUD:user_show.html.twig"
            ))
            ->add('agency.username')
            ->add('agency.email')
            ->add('verifyCondition.verificationConditionText', null, ['label' => 'Condition Type'])
            ->add('verifyDocs', null, array(
                'template' => 'SonataAdminBundle:CRUD:verify_docs.html.twig',
                'header_style' => 'width: 100px'
            ))
            ->add('requestedAt')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'show' => array(),
                    'delete' => array(),
                    'edit' => array(),
                )
            ));
    }

    public function preRemove($object)
    {
        $object->removeFile($object->getVerifyDocs());

    }

    protected function configureShowFields(ShowMapper $show)
    {
        $show
            ->with('Content', ['class' => 'col-md-4'])
            ->add('isVerified')
            ->add('agency.username')
            ->add('agency.email')
            ->add('verifyCondition.verificationConditionText', null, ['label' => 'Condition Type'])
            ->add('requestedAt')
            ->end()
            ->with('Verification Document', ['class' => 'col-md-4'])
            ->add('verifyDocs', null, array(
                'template' => 'SonataAdminBundle:CRUD:verify_docs.html.twig',
                'header_style' => 'width: 100px'
            ))
            ->end()
            ->with('User', ['class' => 'col-md-4'])
            ->add('agency', null, array(
                'template' => "SonataAdminBundle:CRUD:verify_user_show.html.twig"
            ))
            ->end();

    }

    public function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('isVerified');
    }

    public function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
    }

    public function prePersist($image)
    {
        $this->manageFileUpload($image);
    }

    public function postRemove($image)
    {
        $this->manageFileUpload($image);
    }

    public function postUpdate($image)
    {
        if ($image->getisVerified() == true) {
            $container = $this->getConfigurationPool()->getContainer();
            $em = $this->getmodelManager()->getEntityManager("AppBundle:Email");
            $email = $em->getRepository('AppBundle:Email')->find(3);
            $body = $container->get('templating')->render('Emails/confirm_verification_request.html.twig', array(
                'user' => $image->getAgency()->getName(),
                'text' => $email->getEmailText()
            ));
            $message = \Swift_Message::newInstance();
            $message->setSubject($email->getEmailSubject())
                ->setFrom($container->getParameter('mailer_user'))
                ->setTo($image->getAgency()->getEmail())
                ->setBody($body, 'text/html');
            $container->get('mailer')->send($message);
        }
    }

    private function manageFileUpload($image)
    {
        if (file_exists("%kernel.root_dir%/../uploads/verify_docs/" . $image->getVerifyDocs())) {

            unlink("%kernel.root_dir%/../uploads/verify_docs/" . $image->getVerifyDocs());
        }

    }

    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by' => 'requestedAt',
    ];
}