<?php

namespace App\Factory;

use App\Entity\Answer;
use App\Repository\AnswerRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @method static Answer|Proxy createOne(array $attributes = [])
 * @method static Answer[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Answer|Proxy findOrCreate(array $attributes)
 * @method static Answer|Proxy random(array $attributes = [])
 * @method static Answer|Proxy randomOrCreate(array $attributes = [])
 * @method static Answer[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Answer[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static AnswerRepository|RepositoryProxy repository()
 * @method Answer|Proxy create($attributes = [])
 */
final class AnswerFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://github.com/zenstruck/foundry#model-factories)
            'name' => self::faker()->sentence,
            'votes' => self::faker()->randomNumber(),
            'createdAt' => self::faker()->dateTime,
            'updatedAt' => self::faker()->optional(0.3)->dateTime
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Answer $answer) {})
        ;
    }

    protected static function getClass(): string
    {
        return Answer::class;
    }
}
