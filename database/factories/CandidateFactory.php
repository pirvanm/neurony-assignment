<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Candidate>
 */
class CandidateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // @TODO use a table for these, tags for example with a type column to know what if strength or soft skill
        $strengths = ['PHP', 'Laravel', 'Angular', 'React', 'Python', 'Vue.js', 'TailwindCSS', 'Wordpress'];
        shuffle($strengths);
        $softSkills = ['Diplomacy', 'Team player', 'Leadership', 'Sales experience', 'Presentation abilities', 'Public speaking', 'Conflict management'];
        shuffle($softSkills);

        return [
            'user_id' => User::factory()->create()->id,
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'description' => $this->faker->text,
            'strengths' => json_encode(array_slice($strengths, 0, 4)),
            'soft_skills' => json_encode(array_slice($softSkills, 0, 4)),
        ];
    }
}
