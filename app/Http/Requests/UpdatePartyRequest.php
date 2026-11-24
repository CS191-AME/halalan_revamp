<?php

namespace App\Http\Requests;

use App\Models\Election;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdatePartyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'alias' => ['nullable', 'string', 'lt:name'],  // alias must be shorter than name
            'election_id' => ['required', 'integer', 'exists:elections,id'],
            'description' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'string', 'max:255'],  // String temporary for now

        ];
    }

    //  Helper for cleaner after() code
    private function validateElectionMove(Validator $validator): void
    {
        $party = $this->route('party');
        $target = Election::find($this->integer('election_id'));

        if (($target && ($target->id !== $party->election_id))
            && ! ($party->canMoveToElection($target))) {

            $started = collect([$party->election, $target])  // for easy debugging
                ->reject(fn($election) => $election->isDraft())  // say outright problematic elections
                ->pluck('name')
                ->implode(' and ');

            $validator->errors()->add(
                'election_id',
                "This party cannot be moved because {$started} has/have already started."
            );
        }
    }

    public function after(): array
    {
        return [
            fn(Validator $validator) => $this->validateElectionMove($validator),
        ];
    }
}
