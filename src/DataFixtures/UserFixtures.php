<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends BaseFixtures
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->passwordEncoder = $passwordEncoder;
    }

    public function loadData(ObjectManager $manager)
    {
        $this->createManyInt(10, 'main_users', function ($i){
            $user = new User();
            $user->setEmail(sprintf('spacebar%d@example.com', $i));
            $user->setFirstName($this->faker->firstName);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'password'
            ));
            return $user;
        });

        $this->createManyInt(3, 'admin_users', function ($i){
            $user = new User();
            $user->setEmail(sprintf('admin%d@spacebar.com', $i));
            $user->setFirstName($this->faker->firstName);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'password'
            ));
            $user->setRoles(['ROLE_ADMIN']);

            return $user;
        });

        $manager->flush();
    }

    /**
     * @param int $count
     * @param string $groupName
     * @param callable $factory
     */
    private function createManyInt(int $count, string $groupName, callable $factory)
    {
        for ($i = 0; $i < $count; $i++) {
            $entity = $factory($i);
            if (null === $entity) {
                throw new \LogicException('Did you forget to return the entity object from your callback to BaseFixture::createMany()?');
            }
            $this->manager->persist($entity);
            // store for usage later as groupName_#COUNT#
            $this->addReference(sprintf('%s_%d', $groupName, $i), $entity);
        }
    }
}
