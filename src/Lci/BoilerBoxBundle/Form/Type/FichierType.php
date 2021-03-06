<?php
namespace Lci\BoilerBoxBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\FileType;


class FichierType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $option){
		$builder->add('file', FileType::class, array(
			'label' 		=> ' ',
			'label_attr'	=> array('class' => 'label_register')
		));
	}


    /*
     * @param OptionsResolver $resolver
	*/
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Lci\BoilerBoxBundle\Entity\Fichier'
        ));
    }

	public function getName(){
		return 'lci_boilerbox_fichier';
	}
}
