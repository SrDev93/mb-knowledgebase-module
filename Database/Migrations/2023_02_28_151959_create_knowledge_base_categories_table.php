<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKnowledgeBaseCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knowledge_base_categories', function (Blueprint $table) {
            $table->id();
            $table->string('lang', 2)->nullable();
            $table->unsignedBigInteger('brand_id')->nullable();
            $table->integer('sort_id')->default(0);
            $table->unsignedBigInteger('parent_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('knowledge_base_categories');
    }
}
