<?php
namespace App\Serializer;

use App\Entity\Agency;
use App\Repository\AgencyRepository;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class AgencyDenormalizer implements DenormalizerInterface
{
    private $decoratedDenormalizer;
    private $repository;
    public function __construct(DenormalizerInterface $decorated, AgencyRepository $repository) {
        $this->repository = $repository;
        $this->decoratedNormalizer = $decorated;
    }

    public function denormalize($data, $class, $format = null, array $context = array())
    {
        var_dump("denormaliser"); die();
       
        return $this->decoratedDenormalizer->denormalize($data, $class, $context);
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return is_string($data) && Agency::class === $type;
    }
}