<?php
namespace Lci\BoilerBoxBundle\Form\Type;
// Lci/BoilerBox/Form/Type/RegistrationFormType.php

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

use Doctrine\ORM\EntityManager;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegistrationFormType extends BaseType
{
	protected $em;

    /**
     * @var string
     */
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class, EntityManager $em)
    {
        $this->class = $class;
		$this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
			->add('roles', ChoiceType::class, array(
					'label'     => 'Rôles',
					'choices' => $this->fillRoles(),
					'expanded'  => false,
            		'multiple' => true,
					'attr' => array(
                		'placeholder'   => 'Rôle',
                		'class'         => 'select_role'
            		)
				))
                ->add('username', null, array(
                    'label'              => 'Utilisateur', 
                    'translation_domain' => 'FOSUserBundle',
                    'attr'=> array (
                        'placeholder'   => 'Nom utilisateur',
                        'class'         => 'input_txt_large'
                    )
                ))
                ->add('email', EmailType::class, array(
                    'label' => 'form.email', 
                    'translation_domain' => 'FOSUserBundle',
                    'attr'=> array (
                        'placeholder'   => 'adresse mail',
                        'class'         => 'input_txt_large'
                    )
                ))
                ->add('plainPassword', RepeatedType::class, array(
                    'type'              => PasswordType::class,
                    'options'           => array(
						'translation_domain' => 'FOSUserBundle',
						'attr' => array(
                        	'autocomplete' => 'new-password',
                    	),
					),
                    'first_options'     => array(
                        'label' => 'form.password',
                        'attr'  => array (
                            'placeholder'   => 'mot de passe',
                            'class'         => 'input_txt_large'
                        )
                    ),
                    'second_options'    => array(
                        'label' => 'form.password_confirmation',
                        'attr'  => array (
                            'placeholder'   => 'vérification du mot de passe',
                            'class'         => 'input_txt_large'
                        )
                    ),
                    'invalid_message'   => 'fos_user.password.mismatch',
                ))
				->add('enabled', CheckboxType::class, array(
						'label' => 'actif'
				))
                ->add('label', TextType::class, array(
                        'label'     => 'Label',
                        'attr'      => array(
                            'placeholder'   => 'label',
                            'class'         => 'input_txt_large',
                            'max_length'=> '35'
                        ),
                        'required'  => true,
                        'trim'      => true
                ));
    }

    public function getParent() {
	    return BaseType::class;
    }


    /*
     * @param OptionsResolver $resolver
    */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Lci\BoilerBoxBundle\Entity\User',
			'csrf_token_id' => 'registration',
        ));
    }


	public function setDefaultOptions(OptionsResolver $resolver) {
	}


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lci_user_registration';
    }


	// Fonction qui retourne les rôles définis en base de donnée
	private function fillRoles() {
		$tableau_des_roles = array();
		$tab_roles = $this->em->getRepository('LciBoilerBoxBundle:Role')->recuperationDesRoles();
		foreach($tab_roles as $key => $sous_tab_roles) {
			foreach ($sous_tab_roles as $key2 => $role) {
				//$tableau_des_roles[$role] = strtolower(substr($role, 5));
				$tableau_des_roles[strtolower(substr($role, 5))] = $role;
				
			}
		}
		asort($tableau_des_roles);
		return ($tableau_des_roles);
	}
}
