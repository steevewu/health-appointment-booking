<?php

use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //


        Schema::table(
            'patients',
            function (Blueprint $table) {


                $table->dropForeignIdFor(Patient::class, 'ward_id');
                
                $table->dropColumn([
                    'ward_id'
                ]);

                $table->string('address', 15)->nullable();
            }
        );

        Schema::table('doctors', function (Blueprint $table) {


            $table->dropForeignIdFor(Doctor::class, 'ward_id');
            
            
            $table->dropColumn(
                [
                    'ward_id'
                ]
                );


                $table->string('address', 15)->nullable();
        });

        Schema::dropIfExists('wards');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('cities');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //




    }
};
