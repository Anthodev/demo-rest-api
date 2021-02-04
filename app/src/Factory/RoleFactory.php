<?php

namespace App\Factory;

use App\Entity\Role;
use App\Repository\RoleRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @method static Role|Proxy createOne(array $attributes = [])
 * @method static Role[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Role|Proxy findOrCreate(array $attributes)
 * @method static Role|Proxy random(array $attributes = [])
 * @method static Role|Proxy randomOrCreate(array $attributes = [])
 * @method static Role[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Role[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static RoleRepository|RepositoryProxy repository()
 * @method Role|Proxy create($attributes = [])
 */
final class RoleFactory extends ModelFactory
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
            'createdAt' => self::faker()->dateTime,
            'updatedAt' => self::faker()->optional(0.3)->dateTime
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Role $role) {})
        ;
    }

    protected static function getClass(): string
    {
        return Role::class;
    }
}
