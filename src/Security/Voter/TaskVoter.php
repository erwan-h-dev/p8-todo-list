<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TaskVoter extends Voter
{
    public const VIEW = 'VIEW';
    public const EDIT = 'EDIT';
    public const DELETE = 'DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [
            self::EDIT, 
            self::VIEW, 
            self::DELETE
        ]) && $subject instanceof Task;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::VIEW => $this->canView($subject, $user),
            self::EDIT => $this->canEdit($subject, $user),
            self::DELETE => $this->canDelete($subject, $user),
            default => throw new \LogicException('This code should not be reached!'),
        };
    }

    private function canView(Task $task, User $user): bool
    {
        return $user->isAdmin() || $user === $task->getAuteur();
    }

    private function canEdit(Task $task, User $user): bool
    {
        return $user->isAdmin() || $user === $task->getAuteur();
    }

    private function canDelete(Task $task, User $user): bool
    {
        return $user->isAdmin() || $user === $task->getAuteur();
    }
}
