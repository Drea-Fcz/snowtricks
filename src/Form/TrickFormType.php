<?php

namespace App\Form;

use App\Entity\Trick;
use App\Entity\TrickGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickFormType extends AbstractType
{
    public function __construct(private EntityManagerInterface $_em)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $trickGroups = $this->_em->getRepository(TrickGroup::class)->findAll();
        $trickChoices = [];
        foreach ($trickGroups as $trickGroup) {
            $trickChoices[$trickGroup->getName()] = $trickGroup;
        }

        $builder
            ->add('name', TextType::class, [
                'label' => 'Name of the Tick *',
                'help' => 'The figure name must be unique',
                'attr' => array(
                    'placeholder' => 'Ex: Nose Press'
                )
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description *',
                'attr' => array(
                    'placeholder' => 'Ex: A tail press is riding along a flat surface and switching your weight to your back leg while lifting your front leg slightly, which will then lift the front of the board. Whilst a nose press is the opposite, where the weight is shifted into the front.... '
                )
            ])
            ->add('tripGroup', ChoiceType::class, [
                'label' => 'Trick Group *',
                'choices' => $trickChoices
            ])
            ->add('trickMedia', CollectionType::class, [
                'label' => false,
                'entry_type' => TrickMediaFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
