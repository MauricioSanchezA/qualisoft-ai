<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEvaluationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Aquí puedes poner lógica para autorizar al usuario (roles, permisos, etc).
        // Por ahora devolvemos true para permitir a cualquiera que esté autenticado.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string,\Illuminate\Contracts\Validation\ValidationRule|mixed>
     */
    public function rules()
    {
        return [
            'project_id'   => ['required', 'exists:projects,id'],
            'section_key'  => ['required', 'string', 'max:255'],
            'score'        => ['nullable', 'numeric', 'min:0'],
            'comments'     => ['nullable', 'string'],
            'status'       => ['required', Rule::in(['pendiente','completada','archivada'])],
        ];
    }

    /**
     * Custom messages for validation errors (opcional).
     *
     * @return array<string,string>
     */
    public function messages()
    {
        return [
            'project_id.required'  => 'Debe seleccionar un proyecto.',
            'project_id.exists'    => 'El proyecto seleccionado no existe.',
            'section_key.required' => 'Debe indicar la sección a evaluar.',
            'section_key.max'      => 'La sección no puede tener más de :max caracteres.',
            'score.numeric'        => 'La puntuación debe ser un número.',
            'status.required'      => 'Debe indicar el estado de la evaluación.',
            'status.in'            => 'El estado seleccionado no es válido.',
        ];
    }
}
