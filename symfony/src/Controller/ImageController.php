<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Gallery;
use App\Entity\Image;
use App\File\DataUrl;
use App\Repository\GalleryRepository;
use App\Repository\ImageRepository;
use Gaufrette\Util\Checksum;
use Gaufrette\Util\Size;
use Imagine\Gd\Imagine;
use JMS\Serializer\Serializer;
use Knp\Bundle\GaufretteBundle\FilesystemMap;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends ApiController
{
    /**
     * @param string $galleryId
     * @return Response
     *
     * @Route("/api/gallery/{galleryId}/images", methods={"GET"})
     */
    public function imagesAction(string $galleryId): Response
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

        /** @var ImageRepository $imageRepository */
        $imageRepository = $manager->getRepository(Image::class);

        /** @var Image[] $images */
        $images = $imageRepository->findBy(['gallery' => $gallery], ['createdAt' => 'DESC']);

        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');

        $data = $serializer->serialize($images, 'json');

        return new Response($data);
    }

    /**
     * @param string $galleryId
     * @param Request $request
     * @return Response
     *
     * @Route("/api/gallery/{galleryId}/image", methods={"POST"})
     */
    public function createImageAction(string $galleryId, Request $request): Response
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

        /** @var Image $imageData */
        $imageData = $serializer->deserialize($request->getContent(), Image::class, 'json');
        $imageData->setAuthor($identity);
        $imageData->setGallery($gallery);

        $this->uploadFromDataUrl($imageData);

        $doctrine = $this->getDoctrine();
        $manager  = $doctrine->getManager();

        $manager->persist($imageData);
        $manager->flush();

        $data = $serializer->serialize($imageData, 'json');

        return new Response($data);
    }

    /**
     * @param string $imageId
     * @return Response
     *
     * @Route("/api/image/{imageId}", methods={"GET"})
     */
    public function findImageAction(string $imageId): Response
    {
        /*$identity = $this->getIdentity();

        if (null === $identity) {
            throw $this->createAccessDeniedException();
        }*/

        $doctrine = $this->getDoctrine();
        $manager  = $doctrine->getManager();

        /** @var ImageRepository $imageRepository */
        $imageRepository = $manager->getRepository(Image::class);

        /** @var null|Image $image */
        $image = $imageRepository->find($imageId);

        if (null === $image) {
            throw $this->createNotFoundException();
        }

        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');

        $data = $serializer->serialize($image, 'json');

        return new Response($data);
    }

    /**
     * @param string $imageId
     * @param Request $request
     * @return Response
     *
     * @Route("/api/image/{imageId}", methods={"POST"})
     */
    public function changeImageAction(string $imageId, Request $request): Response
    {
        $identity = $this->getIdentity();

        if (null === $identity) {
            throw $this->createAccessDeniedException();
        }

        $doctrine = $this->getDoctrine();
        $manager  = $doctrine->getManager();

        /** @var ImageRepository $imageRepository */
        $imageRepository = $manager->getRepository(Image::class);

        /** @var null|Image $image */
        $image = $imageRepository->find($imageId);

        if (null === $image) {
            throw $this->createNotFoundException();
        }

        /** @var Serializer $serializer */
        $serializer = $this->get('jms_serializer');

        /** @var Image $imageData */
        $imageData = $serializer->deserialize($request->getContent(), Image::class, 'json');

        $image->setTitle($imageData->getTitle());
        $image->setDescription($imageData->getDescription());
        $image->setCustomer($imageData->getCustomer());
        $image->setTags($imageData->getTags());
        //$image->setContent($imageData->getContent());

        /*if (null !== $image->getContent()) {
            $this->uploadFromDataUrl($image);
        }*/

        $manager->persist($image);
        $manager->flush();

        $data = $serializer->serialize($image, 'json');

        return new Response($data);
    }

    /**
     * @param string $imageId
     * @return Response
     *
     * @Route("/api/image/{imageId}", methods={"DELETE"})
     */
    public function deleteImageAction(string $imageId): Response
    {
        $identity = $this->getIdentity();

        if (null === $identity) {
            throw $this->createAccessDeniedException();
        }

        $doctrine = $this->getDoctrine();
        $manager  = $doctrine->getManager();

        /** @var ImageRepository $imageRepository */
        $imageRepository = $manager->getRepository(Image::class);

        /** @var null|Image $image */
        $image = $imageRepository->find($imageId);

        if (null === $image) {
            throw $this->createNotFoundException();
        }

        $manager->remove($image);
        $manager->flush();

        return new Response();
    }

    /**
     * @param Image $imageData
     */
    private function uploadFromDataUrl(Image $imageData): void
    {
        $dataUrl   = DataUrl::create($imageData->getContent());
        $content   = $dataUrl->getBlob();
        $extension = $dataUrl->getExtension();
        $mimeType  = $dataUrl->getMimeType();

        $checksum  = Checksum::fromContent($content);
        $size      = Size::fromContent($content);

        $path    = sprintf('%s.%s', $checksum, $extension);
        $webPath = sprintf('/uploads/%s', $path);

        $imageData->setContent($content);
        $imageData->setExtension($extension);
        $imageData->setMimeType($mimeType);
        $imageData->setChecksum($checksum);
        $imageData->setPath($path);
        $imageData->setWebPath($webPath);
        $imageData->setSize($size);

        $this->calculateImageDimensions($imageData);

        /** @var FilesystemMap $filesystemMap */
        $filesystemMap = $this->get('knp_gaufrette.filesystem_map');
        $filesystem    = $filesystemMap->get('local_filesystem');

        $file = $filesystem->createFile($path);
        $file->setContent($content, ['contentType' => $mimeType]);

        $imageData->setContent(null);
    }

    /**
     * @param Image $imageData
     */
    private function calculateImageDimensions(Image $imageData): void
    {
        $content = $imageData->getContent();

        $imagine = new Imagine();
        $image   = $imagine->load($content);
        $size    = $image->getSize();

        $imageData->setWidth($size->getWidth());
        $imageData->setHeight($size->getHeight());
    }
}
