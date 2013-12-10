<?php

namespace Konkurencia\CommonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Konkurencia\CommonBundle\Form\DataTransformer\AdminFileTransformer;

class KrcAdminImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new AdminFileTransformer();
        $builder->addViewTransformer($transformer);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'invalid_message' => 'The image couldn\'t be saved',
        ));
    }

    public function getParent()
    {
        return 'file';
    }

    public function getName()
    {
        return 'krc_admin_image';
    }
}
