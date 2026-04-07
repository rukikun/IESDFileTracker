<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Document;
use App\Models\Archive;
use Illuminate\Support\Facades\DB;

class ArchiveTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a super admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@test.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'role' => 'super_admin',
            ]
        );

        // Get or create a regular user
        $regularUser = User::firstOrCreate(
            ['email' => 'user@test.com'],
            [
                'name' => 'Regular User',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]
        );

        // Get a category
        $category = Category::first();
        if (!$category) {
            $category = Category::create([
                'category_name' => 'Test Documents',
                'description' => 'Test category for archive demonstration',
                'color' => '#3B82F6',
            ]);
        }

        // Create some test documents and archive entries
        $this->createTestArchiveEntries($superAdmin, $regularUser, $category);

        $this->command->info('Archive test data seeded successfully!');
        $this->command->info('Login as superadmin@test.com with password: password to test archives');
    }

    private function createTestArchiveEntries($superAdmin, $regularUser, $category): void
    {
        // Test 1: Document Creation
        $document1 = Document::create([
            'category_id' => $category->id,
            'category_name' => $category->category_name,
            'document_name' => 'Annual Report 2024',
            'url' => 'https://example.com/annual-report-2024.pdf',
            'document_year' => 2024,
            'document_month_id' => 3,
            'document_date' => now(),
            'is_admin' => false,
        ]);

        Archive::logAction(
            userId: $superAdmin->id,
            action: 'created',
            documentTitle: $document1->document_name,
            documentType: 'document',
            newData: $document1->toArray(),
            description: "Document '{$document1->document_name}' added to {$category->category_name}",
            ipAddress: '127.0.0.1'
        );

        // Test 2: Document Update
        $oldData = $document1->toArray();
        $document1->update([
            'document_name' => 'Annual Report 2024 - Updated',
            'url' => 'https://example.com/annual-report-2024-updated.pdf',
        ]);

        Archive::logAction(
            userId: $regularUser->id,
            action: 'edited',
            documentTitle: $document1->document_name,
            documentType: 'document',
            oldData: $oldData,
            newData: $document1->toArray(),
            description: "Document '{$document1->document_name}' updated in {$category->category_name}",
            ipAddress: '192.168.1.100'
        );

        // Test 3: Document Creation by Regular User
        $document2 = Document::create([
            'category_id' => $category->id,
            'category_name' => $category->category_name,
            'document_name' => 'Meeting Minutes March 2024',
            'url' => 'https://example.com/meeting-minutes-march-2024.pdf',
            'document_year' => 2024,
            'document_month_id' => 3,
            'document_date' => now()->subDays(5),
            'is_admin' => false,
        ]);

        Archive::logAction(
            userId: $regularUser->id,
            action: 'created',
            documentTitle: $document2->document_name,
            documentType: 'document',
            newData: $document2->toArray(),
            description: "Document '{$document2->document_name}' added to {$category->category_name}",
            ipAddress: '192.168.1.100'
        );

        // Test 4: Document Deletion
        $document3 = Document::create([
            'category_id' => $category->id,
            'category_name' => $category->category_name,
            'document_name' => 'Draft Proposal Q1 2024',
            'url' => 'https://example.com/draft-proposal-q1-2024.pdf',
            'document_year' => 2024,
            'document_month_id' => 2,
            'document_date' => now()->subDays(10),
            'is_admin' => false,
        ]);

        Archive::logAction(
            userId: $superAdmin->id,
            action: 'created',
            documentTitle: $document3->document_name,
            documentType: 'document',
            newData: $document3->toArray(),
            description: "Document '{$document3->document_name}' added to {$category->category_name}",
            ipAddress: '127.0.0.1'
        );

        // Now delete it to create a deletion archive entry
        $oldData = $document3->toArray();
        $document3->delete();

        Archive::logAction(
            userId: $superAdmin->id,
            action: 'deleted',
            documentTitle: $oldData['document_name'],
            documentType: 'document',
            oldData: $oldData,
            description: "Document '{$oldData['document_name']}' deleted from {$category->category_name}",
            ipAddress: '127.0.0.1'
        );

        // Test 5: Category Creation (if we want to track category changes too)
        $testCategory = Category::create([
            'category_name' => 'Test Category',
            'description' => 'A test category for demonstration',
            'color' => '#10B981',
        ]);

        Archive::logAction(
            userId: $superAdmin->id,
            action: 'created',
            documentTitle: $testCategory->category_name,
            documentType: 'category',
            newData: $testCategory->toArray(),
            description: "Category '{$testCategory->category_name}' created",
            ipAddress: '127.0.0.1'
        );
    }
}
