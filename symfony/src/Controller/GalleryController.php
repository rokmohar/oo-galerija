<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Gallery;
use App\Repository\GalleryRepository;
use JMS\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GalleryController extends ApiController
{
    /**
     * @return Response
     *
     * @Route("/api/galleries", methods={"GET"})
     */
    public function galleriesAction(): Response
    {
        /*$identity = $this->getIdentity();

        if (null === $identity) {
            throw $this->createAccessDeniedException();
        }*/

        $doctrine = $this->getDoctrine();
        $manager  = $doctrine->getManager();

        /** @var GalleryRepository $galleryRepository */
        $galleryRepository = $manager->getRepository(Gallery::class);

        /** @var Gallery[] $galleries */
        $galleries = $galleryRepository->findBy([], ['createdAt' => 'DESC']);

        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');

        $data = $serializer->serialize($galleries, 'json');

        return new Response($data);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/api/gallery", methods={"POST"})
     */
    public function createGalleryAction(Request $request): Response
    {
        $identity = $this->getIdentity();

        if (null === $identity) {
            throw $this->createAccessDeniedException();
        }

        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');

        /** @var Gallery $gallery */
        $gallery = $serializer->deserialize($request->getContent(), Gallery::class, 'json');
        $gallery->setAuthor($identity);

        $doctrine = $this->getDoctrine();
        $manager  = $doctrine->getManager();

        $manager->persist($gallery);
        $manager->flush();

        $data = $serializer->serialize($gallery, 'json');

        return new Response($data);
    }

    /**
     * @param string $galleryId
     * @return Response
     *
     * @Route("/api/gallery/{galleryId}", methods={"GET"})
     */
    public function findGalleryAction(string $galleryId): Response
    {
        /*$identity = $this->getIdentity();

        if (null === $identity) {
            throw $this->createAccessDeniedException();
        }*/

        $doctrine = $this->getDoctrine();
        $manager  = $doctrine->getManager();

        /** @var GalleryRepository $galleryRepository */
        $galleryRepository = $manager->getRepository(Gallery::class);

        /** @var null|Gallery $gallery */
        $gallery = $galleryRepository->find($galleryId);

        if (null === $gallery) {
            throw $this->createNotFoundException();
        }

        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');

        $data = $serializer->serialize($gallery, 'json');

        return new Response($data);
    }

    /**
     * @param string $galleryId
     * @param Request $request
     * @return Response
     *
     * @Route("/api/gallery/{galleryId}", methods={"POST"})
     */
    public function changeGalleryAction(string $galleryId, Request $request): Response
    {
        $identity = $this->getIdentity();

        if (null === $identity) {
            throw $this->createAccessDeniedException();
        }

        $doctrine = $this->getDoctrine();
        $manager  = $doctrine->getManager();

        /** @var GalleryRepository $galleryRepository */
        $galleryRepository = $manager->getRepository(Gallery::class);

        /** @var null|Gallery $gallery */
        $gallery = $galleryRepository->find($galleryId);

        if (null === $gallery) {
            throw $this->createNotFoundException();
        }

        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');

        /** @var Gallery $galleryData */
        $galleryData = $serializer->deserialize($request->getContent(), Gallery::class, 'json');
        $galleryData->setAuthor($identity);

        $gallery->setName($galleryData->getName());
        $gallery->setDescription($galleryData->getDescription());

        $manager->persist($gallery);
        $manager->flush();

        $data = $serializer->serialize($gallery, 'json');

        return new Response($data);
    }

    /**
     * @param string $galleryId
     * @return Response
     *
     * @Route("/api/gallery/{galleryId}", methods={"DELETE"})
     */
    public function deleteGalleryAction(string $galleryId): Response
    {
        $identity = $this->getIdentity();

        if (null === $identity) {
            throw $this->createAccessDeniedException();
        }

        $doctrine = $this->getDoctrine();
        $manager  = $doctrine->getManager();

        /** @var GalleryRepository $galleryRepository */
        $galleryRepository = $manager->getRepository(Gallery::class);

        /** @var null|Gallery $gallery */
        $gallery = $galleryRepository->find($galleryId);

        if (null === $gallery) {
            throw $this->createNotFoundException();
        }

        $manager->remove($gallery);
        $manager->flush();

        return new Response();
    }
}
