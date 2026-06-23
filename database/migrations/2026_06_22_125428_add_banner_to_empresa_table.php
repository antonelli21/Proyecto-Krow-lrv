public function up(): void
{
    Schema::table('empresa', function (Blueprint $table) {
        $table->string('banner', 255)->nullable()->after('logo');
    });
}

public function down(): void
{
    Schema::table('empresa', function (Blueprint $table) {
        $table->dropColumn('banner');
    });
}
