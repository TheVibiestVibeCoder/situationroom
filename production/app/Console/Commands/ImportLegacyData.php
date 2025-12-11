<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Workspace;
use App\Models\Entry;

class ImportLegacyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:legacy {json-file} {workspace-id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from old daten.json file into a workspace';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->argument('json-file');
        $workspaceId = $this->argument('workspace-id');

        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return 1;
        }

        $workspace = Workspace::find($workspaceId);

        if (!$workspace) {
            $this->error("Workspace not found with ID: {$workspaceId}");
            return 1;
        }

        $data = json_decode(file_get_contents($file), true);

        if (!is_array($data)) {
            $this->error("Invalid JSON file");
            return 1;
        }

        $this->info("Importing into workspace: {$workspace->name}");

        $imported = 0;

        foreach ($data as $entry) {
            // Map old field names to new ones
            $category = $entry['thema'] ?? $entry['category'] ?? 'kreativ';
            $text = $entry['text'] ?? '';
            $visible = $entry['visible'] ?? false;

            if (empty($text)) {
                continue;
            }

            Entry::create([
                'workspace_id' => $workspace->id,
                'category' => $category,
                'text' => $text,
                'visible' => $visible,
                'focused' => false,
            ]);

            $imported++;
        }

        $this->info("✅ Imported {$imported} entries!");
        return 0;
    }
}
