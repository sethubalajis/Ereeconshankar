<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('membership_plans') && ! Schema::hasColumn('membership_plans', 'discount_percentage')) {
            Schema::table('membership_plans', function (Blueprint $table) {
                $table->decimal('discount_percentage', 5, 2)->default(0)->after('fee');
            });
        }

        if (Schema::hasTable('discount_rules')) {
            Schema::table('discount_rules', function (Blueprint $table) {
                if (! Schema::hasColumn('discount_rules', 'product_id')) {
                    $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
                }
                if (! Schema::hasColumn('discount_rules', 'brand_id')) {
                    $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
                }
                if (! Schema::hasColumn('discount_rules', 'product_category_id')) {
                    $table->foreignId('product_category_id')->nullable()->constrained('product_categories')->nullOnDelete();
                }
                if (! Schema::hasColumn('discount_rules', 'price_list_id')) {
                    $table->foreignId('price_list_id')->nullable()->constrained('price_lists')->nullOnDelete();
                }
            });
        }

        if (Schema::hasTable('festival_offers')) {
            Schema::table('festival_offers', function (Blueprint $table) {
                if (! Schema::hasColumn('festival_offers', 'discount_amount')) {
                    $table->decimal('discount_amount', 15, 2)->nullable()->after('discount_percentage');
                }
                if (! Schema::hasColumn('festival_offers', 'product_id')) {
                    $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
                }
                if (! Schema::hasColumn('festival_offers', 'brand_id')) {
                    $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
                }
                if (! Schema::hasColumn('festival_offers', 'product_category_id')) {
                    $table->foreignId('product_category_id')->nullable()->constrained('product_categories')->nullOnDelete();
                }
            });
        }

        if (Schema::hasTable('price_lists') && ! Schema::hasColumn('price_lists', 'discount_percentage')) {
            Schema::table('price_lists', function (Blueprint $table) {
                $table->decimal('discount_percentage', 5, 2)->default(0)->after('code');
            });
        }

        if (Schema::hasTable('combo_offers')) {
            Schema::table('combo_offers', function (Blueprint $table) {
                if (! Schema::hasColumn('combo_offers', 'discount_percentage')) {
                    $table->decimal('discount_percentage', 5, 2)->nullable()->after('total_price');
                }
                if (! Schema::hasColumn('combo_offers', 'discount_amount')) {
                    $table->decimal('discount_amount', 15, 2)->nullable()->after('discount_percentage');
                }
            });
        }

        if (Schema::hasTable('pos_sales') && ! Schema::hasColumn('pos_sales', 'applied_offers')) {
            Schema::table('pos_sales', function (Blueprint $table) {
                $table->json('applied_offers')->nullable()->after('payments');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pos_sales') && Schema::hasColumn('pos_sales', 'applied_offers')) {
            Schema::table('pos_sales', function (Blueprint $table) {
                $table->dropColumn('applied_offers');
            });
        }

        if (Schema::hasTable('combo_offers')) {
            Schema::table('combo_offers', function (Blueprint $table) {
                if (Schema::hasColumn('combo_offers', 'discount_percentage')) {
                    $table->dropColumn('discount_percentage');
                }
                if (Schema::hasColumn('combo_offers', 'discount_amount')) {
                    $table->dropColumn('discount_amount');
                }
            });
        }

        if (Schema::hasTable('price_lists') && Schema::hasColumn('price_lists', 'discount_percentage')) {
            Schema::table('price_lists', function (Blueprint $table) {
                $table->dropColumn('discount_percentage');
            });
        }

        if (Schema::hasTable('festival_offers')) {
            Schema::table('festival_offers', function (Blueprint $table) {
                $columnsToDrop = [];
                if (Schema::hasColumn('festival_offers', 'product_category_id')) $columnsToDrop[] = 'product_category_id';
                if (Schema::hasColumn('festival_offers', 'brand_id')) $columnsToDrop[] = 'brand_id';
                if (Schema::hasColumn('festival_offers', 'product_id')) $columnsToDrop[] = 'product_id';
                if (Schema::hasColumn('festival_offers', 'discount_amount')) $columnsToDrop[] = 'discount_amount';
                if (!empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }
            });
        }

        if (Schema::hasTable('discount_rules')) {
            Schema::table('discount_rules', function (Blueprint $table) {
                $columnsToDrop = [];
                if (Schema::hasColumn('discount_rules', 'price_list_id')) $columnsToDrop[] = 'price_list_id';
                if (Schema::hasColumn('discount_rules', 'product_category_id')) $columnsToDrop[] = 'product_category_id';
                if (Schema::hasColumn('discount_rules', 'brand_id')) $columnsToDrop[] = 'brand_id';
                if (Schema::hasColumn('discount_rules', 'product_id')) $columnsToDrop[] = 'product_id';
                if (!empty($columnsToDrop)) {
                    $table->dropColumn($columnsToDrop);
                }
            });
        }

        if (Schema::hasTable('membership_plans') && Schema::hasColumn('membership_plans', 'discount_percentage')) {
            Schema::table('membership_plans', function (Blueprint $table) {
                $table->dropColumn('discount_percentage');
            });
        }
    }
};
