<?php

namespace App\Factory;

use App\Entity\Poll;
use App\Repository\PollRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @method static Poll|Proxy createOne(array $attributes = [])
 * @method static Poll[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Poll|Proxy findOrCreate(array $attributes)
 * @method static Poll|Proxy random(array $attributes = [])
 * @method static Poll|Proxy randomOrCreate(array $attributes = [])
 * @method static Poll[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Poll[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static PollRepository|RepositoryProxy repository()
 * @method Poll|Proxy create($attributes = [])
 */
final class PollFactory extends ModelFactory
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
            'title' => self::faker()->unique()->sentence,
            'question' => self::faker()->sentence,
            'endDate' => self::faker()->optional(0.4)->dateTime,
            'doUsersMustBeConnected' => self::faker()->boolean(50),
            'totalVotes' => self::faker()->randomNumber(),
            'createdAt' => self::faker()->dateTime,
            'updatedAt' => self::faker()->optional(0.3)->dateTime
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Poll $poll) {})
        ;
    }

    protected static function getClass(): string
    {
        return Poll::class;
    }
}
