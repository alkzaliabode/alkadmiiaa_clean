<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 2; // ترتيب الظهور في القائمة الجانبية
    protected static ?string $recordTitleAttribute = 'name'; // استخدام الاسم كعنوان السجل 
    protected static ?string $navigationLabel = 'المستخدمين'; // Navigation label in Arabic
    protected static ?string $navigationGroup = 'إدارة المستخدمين'; // Navigation group in Arabic
    protected static ?string $modelLabel = 'مستخدم'; // اسم المفرد للمستخدم
    protected static ?string $pluralModelLabel = 'المستخدمون'; // اسم الجمع للمستخدمين
    protected static ?string $slug = 'users'; // تعيين slug للموارد
    protected static ?string $title = 'إدارة المستخدمين'; // عنوان الصفحة 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('الاسم')
                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->label('البريد الإلكتروني')
                    ->email()
                    ->required(),

                Forms\Components\DateTimePicker::make('email_verified_at')
                    ->label('تاريخ التحقق من البريد'),

                Forms\Components\TextInput::make('password')
                    ->label('كلمة المرور')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context) => $context === 'create'),

                Forms\Components\Select::make('roles')
                    ->label('الدور')
                    ->relationship('roles', 'name') // العلاقة بين المستخدم والدور
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('البريد الإلكتروني')
                    ->searchable(),

                Tables\Columns\TextColumn::make('roles.name')
                    ->label('الدور')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('تم التحقق في')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('تعديل'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('حذف متعدد'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}