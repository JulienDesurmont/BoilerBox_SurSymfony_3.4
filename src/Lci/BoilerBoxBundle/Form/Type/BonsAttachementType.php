<?php
namespace Lci\BoilerBoxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;





class BonsAttachementType extends AbstractType {
	/**
     * @param FormBuilderInterface $builder
     * @param array $options
    */
	public function buildForm(FormBuilderInterface $builder, array $option) {
		$builder
        ->add('userInitiateur', EntityType::class, array (
            'class'           => 'LciBoilerBoxBundle:User',
            'required'        => true,
            'label'           => 'Initiateur du bon',
            'label_attr'      => array ('class' => 'label_smalltext'),
            'attr'            => array ('class' => 'smallselect'),
            'choice_label'    => 'label',
            'query_builder'   => function(EntityRepository $er){
                return $er->createQueryBuilder('u')->orderBy('u.label', 'ASC');
            },
        ))
        ->add('user', EntityType::class, array (
            'class'           => 'LciBoilerBoxBundle:User',
            'required'        => true,
            'label'           => 'Intervenant',
            'label_attr'      => array ('class' => 'label_smalltext'),
			'attr'			  => array ('class' => 'smallselect'),
			'choice_label'    => 'label',
			'query_builder'   => function(EntityRepository $er){
				return $er->createQueryBuilder('u')
					->where('u.roles LIKE :role')
					->andWhere('u.enabled = :enabled')
        			->setParameter('role', '%ROLE_INTERVENANT_BA%')
					->setParameter('enabled', true)
					->orderBy('u.label', 'ASC');
			},
        ))
        ->add('dateInitialisation', DateType::class, array(
            'label'         => 'Date d\'initialisation du bon',
            'label_attr'    => array ('class' => 'label_smalltext'),
            'widget'        => 'single_text',
            'html5'         => false,
            'format'        => 'dd-MM-yyyy',
            'invalid_message' => 'Format de la date incorrect.',
            'attr'          => array(
                'class'         => 'smallinput',
                'placeholder'   => 'dd/mm/YYYY',
                'maxlength'     => 10
            )
        ))
		->add('numeroBA', TextType::class, array(
			'label' 		=> 'Numéro du bon d\'attachement',
			'label_attr'    => array ('class' => 'label_smalltext'),
			'required' 		=> true,
            'trim' 			=> true,
			'attr' 			=> array(
				'class' 		=> 'biginput centrer',
				'placeholder' 	=> 'XXXXXX',
				'maxlength'     => 6
			)
		))
		->add('numeroAffaire', TextType::class, array(
            'label' 		=> 'Numéro d\'affaire',
			'label_attr'    => array ('class' => 'label_smalltext'),
			'required' 		=> true,
            'trim' 			=> true,
            'attr' 			=> array(
				'class' 		=> 'biginput upper centrer',
				'maxlength' 	=> 7
            )
        ))
        ->add('site', EntityType::class, array(
            'label'         => 'Nom du site',
			'class'			=> 'LciBoilerBoxBundle:SiteBA',
			'choice_label'  => 'intitule',
			'query_builder'	=> function (EntityRepository $er) {
				return $er->createQueryBuilder('ba')
					->orderBy('ba.intitule', 'ASC');
			},
			'required'		=> true,
            'label_attr'    => array ('class' => 'label_smalltext'),
            'attr'          => array('class' => 'smallselect'),
        ))
		->add('dateSignature', DateType::class, array(
			'label' 		=> 'Date de la signature',
			'label_attr'    => array ('class' => 'label_smalltext'),
			'widget' 		=> 'single_text',
			'html5'			=> false,
			'format'        => 'dd-MM-yyyy',
            'invalid_message' => 'Format de la date incorrect.',
			'attr' 			=> array(
				'class' 		=> 'smallinput',
				'placeholder' 	=> 'dd/mm/YYYY',
				'maxlength' 	=> 10
			)
		))
        ->add('dateDebutIntervention', DateType::class, array(
            'label'         => 'Date de début d\'intervention',
            'label_attr'    => array ('class' => 'label_smalltext'),
            'widget'        => 'single_text',
            'html5'         => false,
            'format'        => 'dd-MM-yyyy',
            'invalid_message' => 'Format de la date incorrect.',
            'attr'          => array(
                'class'         => 'smallinput',
                'placeholder'   => 'dd/mm/YYYY',
                'maxlength'     => 10
            )
        ))
        ->add('dateFinIntervention', DateType::class, array(
            'label'         => 'Date de fin d\'intervention',
            'label_attr'    => array ('class' => 'label_smalltext'),
            'widget'        => 'single_text',
            'html5'         => false,
            'format'        => 'dd-MM-yyyy',
            'invalid_message' => 'Format de la date incorrect.',
            'attr'          => array(
                'class'         => 'smallinput',
                'placeholder'   => 'dd/mm/YYYY',
                'maxlength'     => 10
            )
        ))
		->add('nomDuContact', TextType::class, array(
			'label'			=> 'Nom du contact client',
			'label_attr'    => array ('class' => 'label_smalltext'),
            'required'      => true,
            'trim'          => true,
            'attr'          => array(
                'class'         => 'biginput centrer'
            )
		))
		->add('emailContactClient', TextType::class, array(
			'label' 		=> 'Email du contact',
			'label_attr'    => array ('class' => 'label_smalltext'),
			'required' 		=> true,
            'trim' 			=> true,
			'attr' 			=> array ( 
				'class' 		=> 'biginput',
				'placeholder' 	=> ''
			)
		))
		->add('fichiersPdf', CollectionType::class, array(
			'entry_type'    => FichierType::class,
			/* Option à ajouter pour résoudre l'erreur -> Warning: spl_object_hash() expects parameter 1 to be object, array given */
            'entry_options'	=> array('data_class' => 'Lci\BoilerBoxBundle\Entity\Fichier'),
			'label' 		=> 'Fichier(s) pdf du bon',
			'label_attr'    => array ('class' => 'label_smalltext'),
			'allow_add'		=> true,
			'allow_delete'	=> true,
			'required' 		=> true
		));
	}


    /*
     * @param OptionsResolver $resolver
    */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Lci\BoilerBoxBundle\Entity\BonsAttachement'
        ));
    }


    /**
     * @return string
    */
    public function getName(){
        return 'lci_boilerboxbundle_bonsAttachement';
    }

}
