import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

export function convert(amount: number, from: 'MXN' | 'USD') {

    const page = usePage();
    const exchange = computed(() => page.props.exchange);

    if (from === 'USD') {
        return amount * exchange.value.rate
    }

    if (from === 'MXN') {
        return amount / exchange.value.rate
    }

    return amount
}