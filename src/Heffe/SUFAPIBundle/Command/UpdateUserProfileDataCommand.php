<?php

namespace Heffe\SUFAPIBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateUserProfileDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('heffe:updateProfileData')
            ->setDescription('Update the profile data for all users registered in the application.')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $users = $em->getRepository('HeffeSteamOpenIdBundle:User')->findAll();

        $webAPIService = $this->getContainer()->get('heffe_sufapi.steamuserwebapi');

        foreach($users as $user)
        {
            $output->writeln( sprintf('Fetching profile data for user %s', $user->getSteamId()) );
            $profileData = $webAPIService->getUserProfileData($user->getSteamId());
            if($profileData != null)
            {
                $output->writeln(sprintf('New persona: %s', $profileData->getPersonaName()));
                $user->setPersona( htmlspecialchars($profileData->getPersonaName()) );
                $user->setAvatarURL($profileData->getAvatarUrl());
                $user->setDateUpdated(new \DateTime());

                $em->persist($user);
                $em->flush();
            }
        }

        $output->writeln('Done');
    }
}