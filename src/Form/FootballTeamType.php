<?php


namespace App\Form;

use App\Entity\FootballLeague;
use App\Entity\FootballTeam;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FootballTeamType
 * @package App\Form
 */
class FootballTeamType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('strip', TextType::class)
            ->add('footballLeague', EntityType::class, [
                'class' => FootballLeague::class,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FootballTeam::class,
            'csrf_protection' => false,
        ]);
    }

}
