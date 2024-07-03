import { watch } from "vue";
import { debounce } from "lodash";
import {
    parse,
    isValid,
    isWithinInterval,
    endOfMonth,
    isSameDay,
} from "date-fns";
import { fr } from "date-fns/locale";

export function useFilterProducts(
    props,
    filteredProduits,
    selectedCriteres,
    selectedSousCriteres
) {
    const items = props.produits.data ? props.produits.data : props.produits;
    const filterProducts = () => {
        if (selectedCriteres.value.length === 0) {
            filteredProduits.value = items;
        } else {
            filteredProduits.value = items.filter((produit) => {
                return selectedCriteres.value.every((selectedCritereEntry) => {
                    const [critereId, selectedCritere] = selectedCritereEntry;
                    const numericCritereId = parseInt(critereId);

                    if (
                        selectedCritere === null ||
                        selectedCritere.length === 0 ||
                        selectedCritere.inclus_all === true
                    ) {
                        return true;
                    }

                    return (
                        produit.criteres.some((produitCritere) => {
                            if (
                                numericCritereId !== produitCritere.critere_id
                            ) {
                                return false;
                            }

                            if (
                                typeof selectedCritere === "object" &&
                                "monthStart" in selectedCritere &&
                                "monthEnd" in selectedCritere &&
                                selectedCritere.monthStart !== null &&
                                selectedCritere.monthEnd !== null &&
                                produitCritere.critere.type_champ_form ===
                                    "mois"
                            ) {
                                // MONTHS
                                const startMonth = new Date(
                                    selectedCritere.monthStart
                                );
                                const endMonth = new Date(
                                    selectedCritere.monthEnd
                                );

                                if (isValid(startMonth) && isValid(endMonth)) {
                                    const [startMonthStr, endMonthStr] =
                                        produitCritere.valeur.split(" à ");
                                    const prodStartMonth = parse(
                                        startMonthStr.trim(),
                                        "MMMM yyyy",
                                        new Date(),
                                        { locale: fr }
                                    );
                                    const prodEndMonth = parse(
                                        endMonthStr.trim(),
                                        "MMMM yyyy",
                                        new Date(),
                                        { locale: fr }
                                    );

                                    if (
                                        isValid(prodStartMonth) &&
                                        isValid(prodEndMonth)
                                    ) {
                                        const lastDayProdEndMonth =
                                            endOfMonth(prodEndMonth);
                                        return (
                                            isWithinInterval(startMonth, {
                                                start: prodStartMonth,
                                                end: lastDayProdEndMonth,
                                            }) &&
                                            isWithinInterval(endMonth, {
                                                start: prodStartMonth,
                                                end: lastDayProdEndMonth,
                                            })
                                        );
                                    }
                                }
                            } else if (
                                typeof selectedCritere === "object" &&
                                "debut" in selectedCritere &&
                                "fin" in selectedCritere &&
                                selectedCritere.debut !== null &&
                                selectedCritere.fin !== null &&
                                produitCritere.critere.type_champ_form ===
                                    "times"
                            ) {
                                // TIMES OK
                                const startTime = new Date(
                                    selectedCritere.debut
                                );
                                const endTime = new Date(selectedCritere.fin);

                                if (isValid(startTime) && isValid(endTime)) {
                                    const [startTimeStr, endTimeStr] =
                                        produitCritere.valeur.split(" à ");

                                    // Function to parse time string (hh:mm) to a Date object
                                    const parseTime = (timeStr) => {
                                        const [hours, minutes] = timeStr
                                            .split("h")
                                            .map(Number);
                                        const date = new Date();
                                        date.setHours(hours, minutes, 0, 0);
                                        return date;
                                    };

                                    const prodStartTime = parseTime(
                                        startTimeStr.trim()
                                    );
                                    const prodEndTime = parseTime(
                                        endTimeStr.trim()
                                    );

                                    return (
                                        startTime >= prodStartTime &&
                                        endTime <= prodEndTime
                                    );
                                }
                            } else if (
                                selectedCritere instanceof Date &&
                                selectedCritere !== null
                            ) {
                                // DATE ok
                                if (
                                    produitCritere.critere.type_champ_form ===
                                    "date"
                                ) {
                                    const produitDate = parse(
                                        produitCritere.valeur,
                                        "d MMMM yyyy",
                                        new Date(),
                                        { locale: fr }
                                    );
                                    if (isValid(produitDate)) {
                                        return isSameDay(
                                            selectedCritere,
                                            produitDate
                                        );
                                    }
                                } else if (
                                    produitCritere.critere.type_champ_form ===
                                    "time"
                                ) {
                                    // TIME OK
                                    const valeur = produitCritere.valeur;
                                    const selectedHours =
                                        selectedCritere.getHours();
                                    const selectedMinutes =
                                        selectedCritere.getMinutes();

                                    // Construct a time string in the same format as valeur
                                    const selectedTime = `${String(
                                        selectedHours
                                    ).padStart(2, "0")}h${String(
                                        selectedMinutes
                                    ).padStart(2, "0")}`;
                                    return valeur === selectedTime;
                                }
                            } else if (Array.isArray(selectedCritere)) {
                                return selectedCritere.some(
                                    (critereInArray) => {
                                        if (
                                            critereInArray === null ||
                                            critereInArray.inclus_all === true
                                        ) {
                                            return true;
                                        } else if (
                                            Array.isArray(selectedCritere) &&
                                            selectedCritere.every(
                                                (date) => date instanceof Date
                                            )
                                        ) {
                                            // DATES
                                            return produit.criteres.some(
                                                (produitCritere) => {
                                                    if (
                                                        numericCritereId ===
                                                        produitCritere.critere_id
                                                    ) {
                                                        const [
                                                            startProdDateStr,
                                                            endProdDateStr,
                                                        ] =
                                                            produitCritere.valeur.split(
                                                                " au "
                                                            );
                                                        const startProdDate =
                                                            parse(
                                                                startProdDateStr.trim(),
                                                                "d MMMM yyyy",
                                                                new Date(),
                                                                {
                                                                    locale: fr,
                                                                }
                                                            );
                                                        const endProdDate =
                                                            parse(
                                                                endProdDateStr.trim(),
                                                                "d MMMM yyyy",
                                                                new Date(),
                                                                {
                                                                    locale: fr,
                                                                }
                                                            );

                                                        if (
                                                            isValid(
                                                                startProdDate
                                                            ) &&
                                                            isValid(endProdDate)
                                                        ) {
                                                            return selectedCritere.every(
                                                                (
                                                                    selectedDate
                                                                ) => {
                                                                    return isWithinInterval(
                                                                        selectedDate,
                                                                        {
                                                                            start: startProdDate,
                                                                            end: endProdDate,
                                                                        }
                                                                    );
                                                                }
                                                            );
                                                        }
                                                    }
                                                    return false;
                                                }
                                            );
                                        } else {
                                            return produit.criteres.some(
                                                (produitCritere) => {
                                                    if (
                                                        produitCritere.valeur_id !==
                                                            null &&
                                                        numericCritereId ===
                                                            produitCritere.critere_id
                                                    ) {
                                                        return selectedCritere.some(
                                                            (
                                                                critereInArray
                                                            ) => {
                                                                return (
                                                                    critereInArray !==
                                                                        null &&
                                                                    critereInArray.id ===
                                                                        produitCritere.valeur_id
                                                                );
                                                            }
                                                        );
                                                    }
                                                    return false;
                                                }
                                            );
                                        }
                                    }
                                );
                            } else {
                                // Handle other types (number, string, etc.)
                                if (
                                    produitCritere.valeur_id !== null &&
                                    numericCritereId ===
                                        produitCritere.critere_id
                                ) {
                                    if (typeof selectedCritere === "number") {
                                        // NUMBER
                                        const prodValeurAsNumber = parseFloat(
                                            produitCritere.valeur
                                        );
                                        return (
                                            !isNaN(prodValeurAsNumber) &&
                                            prodValeurAsNumber ===
                                                selectedCritere
                                        );
                                    } else if (
                                        typeof selectedCritere === "string"
                                    ) {
                                        if (selectedCritere.trim() === "") {
                                            return true;
                                        } else {
                                            return (
                                                produitCritere.valeur ===
                                                selectedCritere
                                            );
                                        }
                                    } else {
                                        return (
                                            produitCritere.valeur_id ===
                                                selectedCritere.id ||
                                            (produitCritere.critere_valeur &&
                                                produitCritere.critere_valeur
                                                    .inclus_all === true)
                                        );
                                    }
                                }
                            }
                            return false;
                        }) &&
                        selectedSousCriteres.value.every(
                            (selectedSousCritereEntry) => {
                                const [sousCritereId, selectedSousCritere] =
                                    selectedSousCritereEntry;
                                const numericSousCritereId =
                                    parseInt(sousCritereId);
                                return produit.criteres.some(
                                    (produitCritere) => {
                                        return (
                                            produitCritere.sous_criteres &&
                                            produitCritere.sous_criteres.some(
                                                (sousCritere) => {
                                                    if (
                                                        sousCritere.sous_critere
                                                            .type_champ_form ===
                                                            "range" &&
                                                        selectedSousCritere !==
                                                            null &&
                                                        numericSousCritereId ===
                                                            sousCritere.sous_critere_id
                                                    ) {
                                                        const minValue = 0;
                                                        const maxValue =
                                                            sousCritere.valeur;
                                                        if (
                                                            !isNaN(minValue) &&
                                                            !isNaN(maxValue)
                                                        ) {
                                                            return (
                                                                selectedSousCritere <=
                                                                    maxValue &&
                                                                selectedSousCritere >=
                                                                    minValue
                                                            );
                                                        } else {
                                                            return false;
                                                        }
                                                    } else if (
                                                        sousCritere.sous_critere
                                                            .type_champ_form ===
                                                            "number" &&
                                                        selectedSousCritere !==
                                                            null &&
                                                        numericSousCritereId ===
                                                            sousCritere.sous_critere_id
                                                    ) {
                                                        const prodValeurAsNumber =
                                                            parseFloat(
                                                                sousCritere.valeur
                                                            );
                                                        if (
                                                            !isNaN(
                                                                prodValeurAsNumber
                                                            )
                                                        ) {
                                                            return (
                                                                numericSousCritereId ===
                                                                    sousCritere.sous_critere_id &&
                                                                prodValeurAsNumber ===
                                                                    selectedSousCritere
                                                            );
                                                        } else {
                                                            return false;
                                                        }
                                                    } else if (
                                                        sousCritere.sous_critere
                                                            .type_champ_form ===
                                                            "string" &&
                                                        selectedSousCritere !==
                                                            null
                                                    ) {
                                                        if (
                                                            selectedSousCritere.trim() ===
                                                            ""
                                                        ) {
                                                            return true;
                                                        } else {
                                                            return (
                                                                numericSousCritereId ===
                                                                    sousCritere.sous_critere_id &&
                                                                sousCritere.valeur !==
                                                                    null &&
                                                                sousCritere.valeur ===
                                                                    selectedSousCritere
                                                            );
                                                        }
                                                    } else if (
                                                        numericSousCritereId ===
                                                        sousCritere.sous_critere_id
                                                    ) {
                                                        return (
                                                            sousCritere.sous_critere_valeur_id ===
                                                            selectedSousCritere.id
                                                        );
                                                    } else {
                                                        return false;
                                                    }
                                                }
                                            )
                                        );
                                    }
                                );
                            }
                        )
                    );
                });
            });
        }
    };

    const debouncedFilterProducts = debounce(filterProducts, 300);

    watch([selectedCriteres, selectedSousCriteres], () => {
        debouncedFilterProducts();
    });

    return {
        filteredProduits,
        filterProducts: debouncedFilterProducts,
    };
}
