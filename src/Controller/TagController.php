<?php

namespace App\Controller;

use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    /**
     * @Route("/tag.json", name="tag_list")
     */
    public function list()
    {
        $tags = $this->getDoctrine()->getRepository(Tag::class)->findAll();

        $data = [];
        foreach ($tags as $tag) {
            $data[]['name'] = $tag->getName();
        }

        return $this->json($data);
    }
}
