<?php

namespace AppBundle\Utils\Trick;

use AppBundle\Utils\ThumbnailGenerator;
use Symfony\Component\Form\FormInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TrickTypeHandler
{
    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var ThumbnailGenerator
     */
    private $thumbnailGenerator;

    /**
     * Constructor.
     *
     * @param ObjectManager $entityManager
     */
    public function __construct(
        ObjectManager $entityManager,
        TokenStorageInterface $tokenStorage,
        ThumbnailGenerator $thumbnailGenerator
    ) {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->thumbnailGenerator = $thumbnailGenerator;
    }

    /**
     * Handle a form.
     *
     * @param FormInterface $form
     *
     * @return bool
     */
    public function handle(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            //var_dump($form->getData()); die;
            $trick = $form->getData();

            // Set the authenticated user
            $trick->setUser($this->tokenStorage->getToken()->getUser());

            $this->entityManager->persist($trick);
            $this->entityManager->flush();

            foreach ($trick->getPictures() as $picture) {
                $this->thumbnailGenerator->makeThumb($picture);
            }

            return true;
        }

        return false;
    }
}
