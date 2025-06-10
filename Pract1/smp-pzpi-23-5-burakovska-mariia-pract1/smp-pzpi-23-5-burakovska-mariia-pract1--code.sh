#!/bin/bash
draw_tier() {
    local h=$1
    local tier_max=$2
    local sym=$3
    local global_offset=$(( (snow_param - tier_max) / 2 ))
    for ((r=1; r<=h; r++)); do
        local nsyms=$(( 2*r - 1 ))
        local nspaces=$(( (tier_max - nsyms) / 2 ))
        printf "%*s" $(( global_offset + nspaces )) ""
        for ((j=1; j<=nsyms; j++)); do
            printf "%s" "$sym"
        done
        printf "\n"
        # Чергування символів
        if [ "$sym" = "*" ]; then
            sym="#"
        else
            sym="*"
        fi
    done
    last_sym=$([ "$sym" = "*" ] && echo "#" || echo "*")
}
draw_bottom_tier() {
    local full_rows=$(( (branch_max + 1) / 2 ))
    local global_offset=$(( (snow_param - branch_max) / 2 ))
    local sym
    if [ "$last_sym" = "*" ]; then
        sym="#"
    else
        sym="*"
    fi

    for ((r=2; r<=full_rows; r++)); do
        local count=$(( 2*r - 1 ))
        local nspaces=$(( (branch_max - count) / 2 ))
        printf "%*s" $(( global_offset + nspaces )) ""
        for ((j=1; j<=count; j++)); do
            printf "%s" "$sym"
        done
        printf "\n"
        if [ "$sym" = "*" ]; then
            sym="#"
        else
            sym="*"
        fi
    done
}
if [ "$#" -ne 2 ]; then
    echo "ПОМИЛКА! Невірна кількість аргументів." >&2
    exit 1
fi
for param in "$@"; do
    if ! [[ $param =~ ^[1-9][0-9]*$ ]]; then
        echo "ПОМИЛКА! Аргументи мають бути додатні числа." >&2
        exit 2
    fi
done
total_height=$1
snow_param=$2
if [ "$total_height" -lt 8 ] || [ "$snow_param" -lt 7 ]; then
    echo "ПОМИЛКА! Неможливо зобразити ялинку." >&2
    exit 5
fi
trunk_height=2
snow_height=1
branches_height=$(( total_height - trunk_height - snow_height ))
if [ "$branches_height" -lt 2 ]; then
    echo "ПОМИЛКА! Неможливо зобразити ялинку." >&2
    exit 3
fi
tmp=$(( snow_param - 2 ))
if (( tmp % 2 == 0 )); then
    branch_max=$(( tmp - 1 ))
else
    branch_max=$tmp
fi
full_rows=$(( (branch_max + 1) / 2 ))
tier1_height=$full_rows
if [ "$branches_height" -eq "$branch_max" ]; then
    tier2_height=$(( full_rows - 1 ))
else
    tier2_height=$(( full_rows ))
fi
check_ready=0
until [ "$check_ready" -eq 1 ]; do
    check_ready=1
done
draw_tier "$tier1_height" "$branch_max" "*"
draw_bottom_tier
trunk_padding=$(( (snow_param - 3) / 2 ))
for ((i=1; i<=2; i++)); do
    printf "%*s" "$trunk_padding" ""
    printf "###\n"
done
i=1
snow_width=$(( branch_max + 2 ))
while [ "$i" -le "$snow_width" ]; do
    printf "*"
    ((i++))
done
printf "\n"