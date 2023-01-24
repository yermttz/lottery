<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('key');
            $table->text('value');
            $table->text('description');
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
        
        // Insert some stuff
        DB::table('settings')->insert(
            array(
                [
                    'type' => 'text',
                    'key' => 'title',
                    'value' => 'Titulo',
                    'description' => 'Título del Sistema',
                    'created_by' => 'system',
                    'updated_by' => date('Y-m-d H:i:s')
                ],
                [
                    'type' => 'file',
                    'key' => 'logo',
                    'value' => 'SinLogo',
                    'description' => 'Logo del Sistema',
                    'created_by' => 'system',
                    'updated_by' => date('Y-m-d H:i:s')
                ],
                [
                    'type' => 'text',
                    'key' => 'description',
                    'value' => 'Descripción',
                    'description' => 'Descripción del Sistema',
                    'created_by' => 'system',
                    'updated_by' => date('Y-m-d H:i:s')
                ],
                [
                    'type' => 'text',
                    'key' => 'description2',
                    'value' => 'Descripción',
                    'description' => 'Descripción del Sistema2',
                    'created_by' => 'system',
                    'updated_by' => date('Y-m-d H:i:s')
                ],
                [
                    'type' => 'text',
                    'key' => 'password',
                    'value' => '',
                    'description' => 'Cambiar Clave',
                    'created_by' => 'system',
                    'updated_by' => date('Y-m-d H:i:s')
                ]
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};