<?php

declare (strict_types = 1);

namespace OnlyTracker\Shared\Infrastructure\Symfony\ArgumentResolver;

use OnlyTracker\Shared\Infrastructure\Util\Hydrator\HydratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function is_string;
use function class_exists;
use function is_subclass_of;

class ArgumentValueDtoResolver implements ArgumentValueResolverInterface
{
    private $hydrator;
    private $validator;

    public function __construct(HydratorInterface $hydrator, ValidatorInterface $validator)
    {
        $this->hydrator     = $hydrator;
        $this->validator    = $validator;
    }

    /**
     * {@inheritDoc}
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        $className = $argument->getType();

        return !$argument->isVariadic()
            && !$argument->isNullable()
            && is_string($className)
            && class_exists($className)
            && is_subclass_of($className, IncomingDataInterface::class);
    }

    /**
     * {@inheritDoc}
     * @throws \OnlyTracker\Shared\Infrastructure\Symfony\ArgumentResolver\ErrorValidationException
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $className = $argument->getType();

        $data = $request->getRealMethod() === 'POST'
            ? $request->request->all()
            : $request->query->all();

        $dto = $this->hydrator->hydrate($data, $className);

        $errors = $this->validator->validate($dto);
        if ($errors->count()) {
            throw new ErrorValidationException((string) $errors);
        }

        yield $dto;
    }
}
