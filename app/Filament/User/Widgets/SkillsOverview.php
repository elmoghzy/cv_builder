<?php

namespace App\Filament\User\Widgets;

use App\Models\User;
use Filament\Widgets\Widget;

class SkillsOverview extends Widget
{
    protected static string $view = 'filament.user.widgets.skills-overview';

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        /** @var User $user */
        $user = auth()->user();

        // Get all skills from user's CVs
        $allSkills = collect();
        $user->cvs->each(function ($cv) use ($allSkills) {
            $content = $cv->content ?? [];
            if (isset($content['skills'])) {
                if (is_array($content['skills'])) {
                    $allSkills->push(...$content['skills']);
                } else {
                    $allSkills->push($content['skills']);
                }
            }
        });

        // Get unique skills
        $uniqueSkills = $allSkills->unique()->take(10);

        // Sample skill levels for display
        $skillsWithLevels = $uniqueSkills->map(function ($skill) {
            return [
                'name' => $skill,
                'level' => rand(70, 95), // Random skill level for demo
                'color' => collect(['blue', 'green', 'purple', 'orange', 'pink'])->random()
            ];
        });

        return [
            'skills' => $skillsWithLevels,
            'totalSkills' => $uniqueSkills->count()
        ];
    }
}
