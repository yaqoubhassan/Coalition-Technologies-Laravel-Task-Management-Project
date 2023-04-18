<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            'Project One',
            'Project Two',
            'Project Three',
            'Project Four',
            'Project Five',
            'Project Six',
            'Project Seven'
        ];

        foreach ($projects as $project) {
            Project::create([
                'name' => $project
            ]);
        }
    }
}
