<?php

namespace Lci\BoilerBoxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class SiteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
	    $builder
				->add('intitule', TextType::class, array(
                    'label'         => 'Intitulé',
                    'attr'		    => array (
                        'placeholder'   => 'Intitulé du site',
                        'class'         => 'input_txt_large',
                        'max_length'    => 20
                    ),
                    'required'      => true,
                    'trim'          => true
				))
                ->add('affaire', TextType::class, array(
                    'label'         => 'Code affaire',
                    'attr'		    => array (
                        'placeholder'   => 'Code affaire',
                        'class'         => 'input_txt_large',
                        'max_length'    => 10
                    ),
                    'required'      => true,
                    'trim'          => true
				))
				->add('typeSite', ChoiceType::class, array(
					'label'			=> 'Type',
					'attr'          => array('class' => 'radio_smalltext'),
					'multiple'		=> false,
					'expanded'		=> false,
					'choices'		=> array(
                            'Site'          	=> 'site',
                            'Module'        	=> 'module',
                            'Live de site'     	=> 'live_site',
                            'Live de module'   	=> 'live_module'
					)	
				))
                ->add('url', TextType::class, array(
                    'label'         => 'Url',
                    'attr'		    => array (
                        'placeholder'   => 'URL',
                        'class'         => 'input_txt_large',
                        'max_length'    => 255
                    ),
                    'required'      => true,
                    'trim'          => true
				))
				->add('accesDistant', ChoiceType::class, array(
					'label'			=> 'Site accessible à distance',
					'multiple'		=> false,
					'expanded'		=> true,
					'choices'		=> array(
						'Non' 	=> 0,
						'Oui' 	=> 1
					),
					'attr'          => array('class' => 'radio_smalltext')
				))
				->add('connexion3g', ChoiceType::class, array(
					'label' 		=> 'Connexion 3G',
					'multiple'		=> false,
					'expanded'		=> true,
					'choices'		=> array(
						'Non'	=> 0,
						'Oui'	=> 1
					),
					'attr'			=> array('class' => 'radio_smalltext') 
				))
				->add('connexionAdsl', ChoiceType::class, array(
                    'label'         => 'Connexion Adsl',
                    'multiple'      => false,
                    'expanded'      => true,
                    'choices'       => array(
                    	'Non' => 0,
                        'Oui' => 1
                    ),
                    'attr'          => array('class' => 'radio_smalltext')
                ))
				->add('configBoilerBox', ChoiceType::class, array(
					'label'		=> "Configuration d'accès BoilerBox",
					'expanded'	=> false,
					'multiple'	=> false,
					'choices'	=> array(
						'Incorrecte' => 0,
						'Correcte'   => 1
					)
				));
    }

    /*
     * @param OptionsResolver $resolver
    */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Lci\BoilerBoxBundle\Entity\Site'
        ));
    }


    /**
     * @return string
     */
    public function getName(){
        return 'lci_boilerboxbundle_site';
    }
}
