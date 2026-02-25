<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('techniciens', function (Blueprint $table) {

            // Supprimer utilisateur_id
            $table->dropColumn('utilisateur_id');

            // Ajouter nouvelles colonnes
            $table->string('nom', 100)->after('id');
            $table->string('prenom', 100)->after('nom');
            $table->string('email', 150)->nullable()->after('prenom');
            $table->string('telephone', 20)->nullable()->after('email');
            $table->date('date_embauche')->nullable()->after('tarif_horaire_interne');
        });

        // Modifier ENUM specialite
        DB::statement("
            ALTER TABLE techniciens 
            MODIFY specialite 
            ENUM('mecanique','carrosserie','electricite','diagnostic') 
            NOT NULL DEFAULT 'mecanique'
        ");

        // Modifier ENUM niveau_expertise
        DB::statement("
            ALTER TABLE techniciens 
            MODIFY niveau_expertise 
            ENUM('Junior','Confirme','Expert') 
            NOT NULL DEFAULT 'Junior'
        ");
    }

    public function down(): void
    {
        Schema::table('techniciens', function (Blueprint $table) {

            $table->dropColumn([
                'nom',
                'prenom',
                'email',
                'telephone',
                'date_embauche'
            ]);

            $table->integer('utilisateur_id')->nullable();
        });

        DB::statement("
            ALTER TABLE techniciens 
            MODIFY specialite 
            ENUM('mecanique','carrosserie','electricite') 
            NULL
        ");

        DB::statement("
            ALTER TABLE techniciens 
            MODIFY niveau_expertise 
            ENUM('Junior','Confirme','Expert') 
            NULL
        ");
    }
};
