<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class GenerateCharts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'charts:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate charts using Python script';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generating charts...');

        // Get the path to the Python script
        $scriptPath = base_path('python_scripts/generate_charts.py');

        // Create the process
        $process = new Process(['python', $scriptPath]);
        $process->setTimeout(300); // 5 minutes timeout

        // Run the process
        $process->run(function ($type, $buffer) {
            $this->output->write($buffer);
        });

        if ($process->isSuccessful()) {
            $this->info('Charts generated successfully!');
        } else {
            $this->error('Failed to generate charts: ' . $process->getErrorOutput());
        }
    }
} 