<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Exception;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class BaseController
 * @package AppBundle\Controller
 */
class BaseController extends Controller
{
    /**
     * @var $entityName String
     */
    protected $entityName;
    /**
     * @var $entityAltName String
     */
    protected $entityAltName;
    /**
     * @var $entityAltNamePlu String
     */
    protected $entityAltNamePlu;
    /**
     * @var $prevEntity String
     */
    protected $prevEntity;

    /**
     * @param string $key
     * @param array $params
     * @return string
     */
    protected function t($key, $params = array())
    {
        return $this->get('translator')->trans(
            $key,
            $params
        );
    }

    /**
     * @param LoggerInterface $logger
     * @param String $message
     * @param String $option
     */
    protected function logger(LoggerInterface $logger, $message, $option = null)
    {
        if ($this->getUser() !== null) {
            $userId = (is_null($this->getUser())? "guest" :$this->getUser()->getId());

        }
        $logger->error(
            $message .
            "\nUser id: " . $this->getUser()->getId() .
            "\nUser email: " . $this->getUser()->getEmail() .
            "\n" . $option
        );
    }

    /**
     * @param EntityManager $em
     * @param LoggerInterface $logger
     * @param $objName
     * @return bool
     */
    protected function emptyTable(EntityManager $em, LoggerInterface $logger, $objName)
    {

        $queryBuilder = $this->getDoctrine()->getRepository($objName)->findAll();
        foreach ($queryBuilder as $v) {
            $em->remove($v);
        }
        try {
            $em->flush();
        } catch (OptimisticLockException $e) {
            $this->addFlash(
                'error',
                $this->t('app.error')
            );
            $logger->error($e->getMessage());
            return false;
        }

        $classMetaData = $em->getClassMetadata($objName);
        $connection = $em->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->beginTransaction();
        try {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');
            $q = $dbPlatform->getTruncateTableSql($classMetaData->getTableName());
            $connection->executeUpdate($q);
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        } /** @noinspection PhpRedundantCatchClauseInspection */
        catch (ForeignKeyConstraintViolationException $e) {
            $this->addFlash(
                'error',
                $this->t('app.unique_constraint_error')
            );
            return false;
        } catch (Exception $e) {
            try {
                $connection->rollback();
            } catch (ConnectionException $ce) {
                $this->addFlash(
                    'error',
                    $this->t('app.error')
                );
                $logger->error($e->getMessage());
                return false;
            }
            $this->addFlash(
                'error',
                $this->t('app.error')
            );
            $logger->error($e->getMessage());
            return false;
        }

        $this->addFlash(
            'success',
            $this->t(
                'entity.deleted_all',
                array(
                    '%entity%' => $this->entityName
                )
            )
        );
        return true;
    }

    public function getRequestCountAction(){

        $reqRep = $this->getDoctrine()->getRepository('AppBundle:VerifyRequest')->findBy(['isVerified' => false]);
        $count = count($reqRep);

        return $this->render('dashboard/verification/verify-requested-count.html.twig',
            array(
                'count' => $count
            ));
    }
}
