<?php

namespace App\Form\Type;

use App\Form\DataTransformer\TagsArrayToStringTransformer;
use App\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class TagsInputType extends AbstractType
{
    /**
     * @var TagRepository
     */
    private $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // Transforme une collection Doctrine en tableau
            ->addModelTransformer(new CollectionToArrayTransformer(), true)
            // Transforme un tableau de tags en chaine de caractères
            ->addModelTransformer(
                new TagsArrayToStringTransformer($this->tagRepository),
                true
            )
        ;
    }

    public function getParent()
    {
        return TextType::class;
    }
}
