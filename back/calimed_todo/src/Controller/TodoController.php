<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use App\Entity\Todo;

use Symfony\Component\HttpFoundation\Request;

class TodoController extends AbstractController
{
    private function getSerialized($content)
    {

        $normalizers = [new ObjectNormalizer()];
        $encoders = [new JsonEncoder()];
        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($content, 'json');

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    private function remove(Todo $item)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($item);
        $entityManager->flush();
    }

    /**
      * @Route("/api/flush")
      */
    public function flush()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(Todo::class);
        $items = $repository->findAll();

        foreach ($items as $item) {
            $this->remove($item);
        }

        return $this->json(null);
    }

    /**
      * @Route("/api/list")
      */
    public function list()
    {
        $repository = $this->getDoctrine()->getRepository(Todo::class);
        $items = $repository->findAll();

        $response = $this->getSerialized($items);
        return $response;
    }

    /**
      * @Route("/api/create", methods={"POST"})
      */
    public function create(Request $request)
    {
        $item = $request->request->get('item');
        
        $todo = new Todo();
        $todo->setItem($item);
        $todo->setDone(false);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($todo);
        $entityManager->flush();

        $response = $this->getSerialized($todo);
        return $response;
    }

    /**
      * @Route("/api/edit/{id}", methods={"POST"})
      */
    public function edit(Request $request, int $id)
    {
        $repository = $this->getDoctrine()->getRepository(Todo::class);

        $item = $request->request->get('item');
        $done = $request->request->get('done');
        
        try {
            $todo = $repository->find($id);

            if ($item != null)
                $todo->setItem($item);

            if ($done != null)
                $todo->setDone($done);

            if ($item != null || $done != null) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($todo);
                $entityManager->flush();
            }

        } catch (\Throwable $th) {
            return $this->json($th, 500);
        }
        
        return $this->json($id);
    }

    /**
      * @Route("/api/delete/{id}", methods={"DELETE"})
      */
    public function delete(int $id)
    {
        $repository = $this->getDoctrine()->getRepository(Todo::class);
        
        try {
            $todo = $repository->find($id);
            $this->remove($todo);
        } catch (\Throwable $th) {
            return $this->json("error", 500);
        }
        
        return $this->json($id);
    }
}

?>