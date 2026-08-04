<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use App\Models\BlogPost;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state): void {
                        if (($get('slug') ?? '') !== Str::slug((string) $old)) {
                            return;
                        }

                        $set('slug', Str::slug((string) $state));
                    }),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(BlogPost::class, 'slug', ignoreRecord: true),
                Textarea::make('excerpt')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('category')
                    ->maxLength(100),
                TextInput::make('read_time')
                    ->placeholder('5 min')
                    ->maxLength(50),
                FileUpload::make('image')
                    ->image()
                    ->directory('blog')
                    ->disk('public')
                    ->visibility('public')
                    ->imageEditor(),
                TextInput::make('meta_title')
                    ->maxLength(255)
                    ->helperText('Leave blank to use the post title.'),
                Textarea::make('meta_description')
                    ->rows(2)
                    ->helperText('Leave blank to use the excerpt.'),
                Toggle::make('is_published')
                    ->label('Published')
                    ->default(false),
                DateTimePicker::make('published_at')
                    ->label('Publish date')
                    ->seconds(false),
            ]);
    }
}
