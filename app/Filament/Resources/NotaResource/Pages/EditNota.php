<?php

namespace App\Filament\Resources\NotaResource\Pages;

use App\Filament\Resources\NotaResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditNota extends EditRecord
{
    protected static string $resource = NotaResource::class;
    protected static string $view = 'filament.resources.nota-resource.pages.form-nota-update';


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function update()
{
    $get = $this->form->getState();  // Pega os dados do formulário
    $nota = $this->getRecord();      // Assume que você está na página de edição de uma nota existente

    // Calcula a média, considerando valores nulos como 0 para fins de cálculo
    $notas = collect([$get['p1'], $get['p2'], $get['mac']])->map(function ($item) {
        return $item ?? 0;  // Substitui nulos por 0
    });
    $media = $notas->sum() / 3;

    // Atualiza o registro
    $nota->update([
        'p1' => $get['p1'],
        'p2' => $get['p2'],
        'mac' => $get['mac'],
        'mt' => $media  // Atualiza a média
    ]);

    // Notificação condicional baseada na nota
    if ($get['p1'] < 10) {
        $studentUser = $nota->student->user;  // Acesso ao usuário do estudante associado à nota
        if ($studentUser) {
            Notification::make()
                ->title('Nota Baixa')
                ->success()
                ->body('Sua nota está abaixo do esperado.')
                ->sendToDatabase($studentUser)
                ->send();
        }
    }

    return redirect()->to('admin/notas');
}
}
