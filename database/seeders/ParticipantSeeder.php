<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Participant;
use App\Models\Project;
use App\Models\Vendor;

class ParticipantSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $projects = Project::all();
        $vendors = Vendor::all();

        if ($projects->isEmpty() || $vendors->isEmpty()) {
            $this->command->info('No projects or vendors found. Please create some first.');
            return;
        }

        // Create sample participants for each project
        foreach ($projects as $project) {
            // Create participants with different statuses
            $statuses = [1, 2, 3, 4, 5, 6, 7, 8, 9]; // Complete, Terminate, Quota Full, Security Full, LOI Fail, IR Count, IP Fail, URL Error, Unknown
            
            foreach ($statuses as $status) {
                $count = rand(2, 8); // Random number of participants per status
                
                for ($i = 0; $i < $count; $i++) {
                    $vendor = $vendors->random();
                    
                    Participant::create([
                        'participant_id' => 'ENEVNA' . strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3)) . strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 4)) . date('Ymd'),
                        'project_id' => $project->id,
                        'vendor_id' => $vendor->id,
                        'uid' => 'UID' . mt_rand(100000, 999999),
                        'status' => $status,
                        'loi' => $status == 1 ? rand(5, 30) : null, // Only completed participants have LOI
                        'participant_ip' => '192.168.1.' . rand(1, 254),
                        'start_loi' => now()->subMinutes(rand(5, 60)),
                        'end_loi' => $status == 1 ? now()->subMinutes(rand(1, 5)) : null,
                    ]);
                }
            }
        }

        $this->command->info('Sample participants created successfully!');
    }
}