<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrioritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('priorities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('variant');
            $table->timestamps();
        });


        $data = array(
            array('name'=>'No Priority', 'variant'=>'secondary'),
            array('name'=>'Low Priority', 'variant'=>'info'),
            array('name'=>'Medium Priority', 'variant'=>'warning'),
            array('name'=>'High Priority', 'variant'=>'danger'),
        );
        
        DB::table('priorities')->insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('priorities');
    }
}
