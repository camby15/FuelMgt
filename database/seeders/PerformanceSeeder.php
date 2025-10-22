<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Performance;
use App\Models\Employee;
use App\Models\User;

class PerformanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some sample employees and users
        $employees = Employee::take(5)->get();
        $users = User::take(3)->get();

        if ($employees->isEmpty()) {
            // Create sample employees if none exist
            $employees = collect([
                ['id' => 1, 'company_id' => 1],
                ['id' => 2, 'company_id' => 1],
                ['id' => 3, 'company_id' => 1],
                ['id' => 4, 'company_id' => 1],
                ['id' => 5, 'company_id' => 1],
            ]);
        }

        if ($users->isEmpty()) {
            // Create sample users if none exist
            $users = collect([
                ['id' => 1],
                ['id' => 2],
                ['id' => 3],
            ]);
        }

        // Sample performance reviews
        $performances = [
            [
                'company_id' => 1,
                'employee_id' => $employees->first()['id'] ?? 1,
                'type' => 'self',
                'review_period_start' => '2024-01-01',
                'review_period_end' => '2024-03-31',
                'goals' => 'Complete quarterly sales targets, improve customer satisfaction scores, and enhance team collaboration.',
                'achievements' => 'Exceeded sales targets by 15%, improved customer satisfaction from 4.2 to 4.6, and led team training sessions.',
                'areas_for_improvement' => 'Improve time management and delegation skills.',
                'overall_score' => 4.5,
                'overall_rating' => 'excellent',
                'status' => 'completed',
                'reviewer_id' => $users->first()['id'] ?? 1,
                'notes' => 'Outstanding performance this quarter. Shows strong leadership potential.',
                'created_at' => now()->subDays(30),
                'updated_at' => now()->subDays(25),
            ],
            [
                'company_id' => 1,
                'employee_id' => $employees->skip(1)->first()['id'] ?? 2,
                'type' => 'manager',
                'review_period_start' => '2024-01-01',
                'review_period_end' => '2024-03-31',
                'goals' => 'Lead marketing campaign for new product launch, increase social media engagement by 30%, and mentor junior team members.',
                'achievements' => 'Successfully launched marketing campaign resulting in 25% engagement increase, mentored 2 junior employees.',
                'areas_for_improvement' => 'Improve budget management and campaign analytics reporting.',
                'overall_score' => 4.2,
                'overall_rating' => 'good',
                'status' => 'completed',
                'reviewer_id' => $users->skip(1)->first()['id'] ?? 2,
                'notes' => 'Good performance with room for improvement in analytical skills.',
                'created_at' => now()->subDays(28),
                'updated_at' => now()->subDays(20),
            ],
            [
                'company_id' => 1,
                'employee_id' => $employees->skip(2)->first()['id'] ?? 3,
                'type' => 'self',
                'review_period_start' => '2024-04-01',
                'review_period_end' => '2024-06-30',
                'goals' => 'Complete software development project on time, improve code quality, and participate in code reviews.',
                'achievements' => 'Delivered project ahead of schedule with high code quality scores.',
                'areas_for_improvement' => 'Increase participation in team meetings and knowledge sharing.',
                'overall_score' => null,
                'overall_rating' => null,
                'status' => 'pending',
                'reviewer_id' => $users->first()['id'] ?? 1,
                'notes' => 'Self-assessment submitted, awaiting manager review.',
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(10),
            ],
            [
                'company_id' => 1,
                'employee_id' => $employees->skip(3)->first()['id'] ?? 4,
                'type' => 'manager',
                'review_period_start' => '2023-01-01',
                'review_period_end' => '2023-12-31',
                'goals' => 'Achieve department KPI targets, implement process improvements, and develop team capabilities.',
                'achievements' => 'Met 90% of KPI targets, implemented 3 process improvements, and conducted 5 training sessions.',
                'areas_for_improvement' => 'Focus on strategic planning and long-term goal setting.',
                'overall_score' => 4.0,
                'overall_rating' => 'good',
                'status' => 'completed',
                'reviewer_id' => $users->skip(2)->first()['id'] ?? 3,
                'notes' => 'Solid performance with consistent results throughout the year.',
                'created_at' => now()->subDays(60),
                'updated_at' => now()->subDays(45),
            ],
            [
                'company_id' => 1,
                'employee_id' => $employees->skip(4)->first()['id'] ?? 5,
                'type' => 'manager',
                'review_period_start' => '2024-07-01',
                'review_period_end' => '2024-09-30',
                'goals' => 'Improve customer support response times, reduce ticket resolution time, and enhance customer satisfaction.',
                'achievements' => 'Reduced average response time by 20%, improved customer satisfaction scores.',
                'areas_for_improvement' => 'Work on ticket backlog management and proactive customer communication.',
                'overall_score' => null,
                'overall_rating' => null,
                'status' => 'pending',
                'reviewer_id' => $users->first()['id'] ?? 1,
                'notes' => 'Currently in progress, showing good improvement trends.',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(2),
            ],
            [
                'company_id' => 1,
                'employee_id' => $employees->first()['id'] ?? 1,
                'type' => 'peer',
                'review_period_start' => '2024-04-01',
                'review_period_end' => '2024-06-30',
                'goals' => 'Collaborate effectively with cross-functional teams, share knowledge, and support team goals.',
                'achievements' => 'Successfully collaborated on 3 cross-functional projects, mentored junior colleagues.',
                'areas_for_improvement' => 'Improve communication during high-pressure situations.',
                'overall_score' => 3.8,
                'overall_rating' => 'satisfactory',
                'status' => 'completed',
                'reviewer_id' => $users->skip(1)->first()['id'] ?? 2,
                'notes' => 'Good team player with strong collaboration skills.',
                'created_at' => now()->subDays(20),
                'updated_at' => now()->subDays(15),
            ],
            [
                'company_id' => 1,
                'employee_id' => $employees->skip(1)->first()['id'] ?? 2,
                'type' => 'self',
                'review_period_start' => '2024-10-01',
                'review_period_end' => '2024-12-31',
                'goals' => 'Lead digital transformation initiative, increase online engagement, and develop digital marketing strategies.',
                'achievements' => '',
                'areas_for_improvement' => '',
                'overall_score' => null,
                'overall_rating' => null,
                'status' => 'draft',
                'reviewer_id' => $users->first()['id'] ?? 1,
                'notes' => 'Draft self-assessment, needs completion.',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(1),
            ],
            [
                'company_id' => 1,
                'employee_id' => $employees->skip(2)->first()['id'] ?? 3,
                'type' => 'manager',
                'review_period_start' => '2024-09-01',
                'review_period_end' => '2024-09-30',
                'goals' => 'Complete assigned development tasks, maintain code quality standards, and participate in agile ceremonies.',
                'achievements' => 'Completed all sprint tasks on time, maintained 95% code coverage.',
                'areas_for_improvement' => 'Improve estimation accuracy for complex tasks.',
                'overall_score' => 4.3,
                'overall_rating' => 'excellent',
                'status' => 'completed',
                'reviewer_id' => $users->skip(2)->first()['id'] ?? 3,
                'notes' => 'Excellent technical performance and code quality.',
                'created_at' => now()->subDays(40),
                'updated_at' => now()->subDays(35),
            ],
        ];

        foreach ($performances as $performance) {
            Performance::create($performance);
        }
    }
}
