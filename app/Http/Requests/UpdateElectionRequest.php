<?php

namespace App\Http\Requests;

use App\Models\Election;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateElectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $election = $this->route('election');

        if ($election->isDraft()) {
            $this->merge([
                'ballots_enabled' => $this->boolean('ballots_enabled'),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $election = $this->route('election');

        // Different rules apply depending on whether the election is a draft or not
        if ($election->isDraft()) {
            return [
                'name' => ['required', 'string', 'max:255'],

                'parent_id' => [
                    'nullable',
                    'integer',
                    'exists:elections,id',

                    // Makes sure that the election is not placed under itself or one of its own child elections
                    function ($attribute, $value, $fail) use ($election) {
                        if (
                            $value !== null &&
                            in_array(
                                (int) $value,
                                Election::subtreeIds($election->id),
                                true
                            )
                        ) { $fail('An election cannot be placed under itself or one of its own child elections.'); }
                    },
                ],

                'ballots_enabled' => ['boolean'],
            ];
        } else {
            return [
                'name' => ['required', 'string', 'max:255'],
                'parent_id' => ['prohibited'],
                'ballots_enabled' => ['prohibited'],
            ];
        }
    }
}