<?php

namespace Glare\Litstack\Macros;

use Ignite\Crud\CrudShow;

class CrudMetaMacro
{
    /**
     * Register macro.
     *
     * @return void
     */
    public function register()
    {
        // CrudShow::macro('meta', function () {
        //     $this->card(function ($card) {
        //         $metaMaxWith = '520px';
        //         $card->wrapper('lit-utilities-meta-wrapper', function ($meta) use ($metaMaxWith) {
        //             $meta->input('meta_title')
        //                 ->title('Meta-Title')
        //                 ->placeholder('Meta-Title')
        //                 ->hint(__lit('crud.meta.title_hint', [
        //                     'width' => $metaMaxWith,
        //                 ]));

        //             $meta->input('meta_keywords')
        //                 ->title('Meta-Słowa-Kluczowe')
        //                 ->placeholder('Keyword1, Keyword2, …')
        //                 ->hint(__lit('crud.meta.keywords_hint'));

        //             $meta->input('meta_description')
        //                 ->title('Meta-Opis')
        //                 ->placeholder('Meta-Opis')
        //                 ->hint(__lit('crud.meta.description_hint'))
        //                 ->max(156)
        //                 ->rules('max:156');

        //             $meta->component('lit-utilities-meta');
        //         })->prop('google-meta-max-width', $metaMaxWith);
        //     })->title('Meta-Info');
        // });
    }
}
