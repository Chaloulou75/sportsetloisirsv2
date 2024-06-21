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
                return (
                    selectedCriteres.value.every((selectedCritereEntry) => {
                        const [critereId, selectedCritere] =
                            selectedCritereEntry;
                        const numericCritereId = parseInt(critereId);
                        if (
                            selectedCritere === null ||
                            selectedCritere.inclus_all === true
                        ) {
                            return true;
                        } else if (Array.isArray(selectedCritere)) {
                            return selectedCritere.some((critereInArray) => {
                                if (critereInArray === null) {
                                    return true;
                                } else if (
                                    critereInArray !== null &&
                                    typeof critereInArray === "object" &&
                                    "month" in critereInArray &&
                                    "year" in critereInArray
                                ) {
                                    // MONTHS
                                    const startMonth = selectedCritere[0]
                                        ? new Date(
                                              parseInt(selectedCritere[0].year),
                                              selectedCritere[0].month,
                                              1
                                          )
                                        : null;

                                    const endMonth = selectedCritere[1]
                                        ? new Date(
                                              parseInt(selectedCritere[1].year),
                                              selectedCritere[1].month,
                                              1
                                          )
                                        : null;
                                    if (startMonth && endMonth) {
                                        return produit.criteres.some(
                                            (produitCritere) => {
                                                if (
                                                    numericCritereId ===
                                                    produitCritere.critere_id
                                                ) {
                                                    const [
                                                        startMonthStr,
                                                        endMonthStr,
                                                    ] =
                                                        produitCritere.valeur.split(
                                                            " à "
                                                        );

                                                    const prodStartMonth =
                                                        parse(
                                                            startMonthStr,
                                                            "MMMM yyyy",
                                                            new Date(),
                                                            {
                                                                locale: fr,
                                                            }
                                                        );
                                                    const prodEndMonth = parse(
                                                        endMonthStr,
                                                        "MMMM yyyy",
                                                        new Date(),
                                                        {
                                                            locale: fr,
                                                        }
                                                    );

                                                    if (
                                                        isValid(
                                                            prodStartMonth
                                                        ) &&
                                                        isValid(prodEndMonth)
                                                    ) {
                                                        const lastDayProdEndMonth =
                                                            endOfMonth(
                                                                prodEndMonth
                                                            );
                                                        return (
                                                            isWithinInterval(
                                                                startMonth,
                                                                {
                                                                    start: prodStartMonth,
                                                                    end: lastDayProdEndMonth,
                                                                }
                                                            ) &&
                                                            isWithinInterval(
                                                                endMonth,
                                                                {
                                                                    start: prodStartMonth,
                                                                    end: lastDayProdEndMonth,
                                                                }
                                                            )
                                                        );
                                                    } else {
                                                        return false;
                                                    }
                                                } else {
                                                    return false;
                                                }
                                            }
                                        );
                                    } else {
                                        return false;
                                    }
                                } else if (
                                    critereInArray !== null &&
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
                                                const startProdDate = parse(
                                                    startProdDateStr,
                                                    "d MMMM yyyy",
                                                    new Date(),
                                                    { locale: fr }
                                                );
                                                const endProdDate = parse(
                                                    endProdDateStr,
                                                    "d MMMM yyyy",
                                                    new Date(),
                                                    { locale: fr }
                                                );

                                                if (
                                                    isValid(startProdDate) &&
                                                    isValid(endProdDate)
                                                ) {
                                                    return selectedCritere.every(
                                                        (selectedDate) => {
                                                            return isWithinInterval(
                                                                selectedDate,
                                                                {
                                                                    start: startProdDate,
                                                                    end: endProdDate,
                                                                }
                                                            );
                                                        }
                                                    );
                                                } else {
                                                    return false;
                                                }
                                            }
                                            return false;
                                        }
                                    );
                                } else if (
                                    Array.isArray(selectedCritere) &&
                                    selectedCritere.length === 2 &&
                                    selectedCritere.every(
                                        (timeObj) =>
                                            "hours" in timeObj &&
                                            "minutes" in timeObj
                                    )
                                ) {
                                    // TIMES
                                    return produit.criteres.some(
                                        (produitCritere) => {
                                            if (
                                                numericCritereId ===
                                                produitCritere.critere_id
                                            ) {
                                                const [
                                                    startTimeObj,
                                                    endTimeObj,
                                                ] = selectedCritere;

                                                // Create Date objects for the selected start and end times
                                                const selectedStartTime =
                                                    new Date(
                                                        0,
                                                        0,
                                                        0,
                                                        startTimeObj.hours,
                                                        startTimeObj.minutes
                                                    );
                                                const selectedEndTime =
                                                    new Date(
                                                        0,
                                                        0,
                                                        0,
                                                        endTimeObj.hours,
                                                        endTimeObj.minutes
                                                    );

                                                const [
                                                    startTimeStr,
                                                    endTimeStr,
                                                ] =
                                                    produitCritere.valeur.split(
                                                        " à "
                                                    );

                                                // Parse start time
                                                const [
                                                    startHours,
                                                    startMinutes,
                                                ] = startTimeStr
                                                    .split("h")
                                                    .map(Number);
                                                const produitStartTime =
                                                    new Date(
                                                        0,
                                                        0,
                                                        0,
                                                        startHours,
                                                        startMinutes
                                                    );

                                                // Parse end time
                                                const [endHours, endMinutes] =
                                                    endTimeStr
                                                        .split("h")
                                                        .map(Number);
                                                const produitEndTime = new Date(
                                                    0,
                                                    0,
                                                    0,
                                                    endHours,
                                                    endMinutes
                                                );
                                                // Check if the selected time range falls within the produit time range
                                                if (
                                                    (selectedStartTime,
                                                    selectedEndTime,
                                                    produitStartTime,
                                                    produitEndTime)
                                                ) {
                                                    return (
                                                        isWithinInterval(
                                                            selectedStartTime,
                                                            {
                                                                start: produitStartTime,
                                                                end: produitEndTime,
                                                            }
                                                        ) &&
                                                        isWithinInterval(
                                                            selectedEndTime,
                                                            {
                                                                start: produitStartTime,
                                                                end: produitEndTime,
                                                            }
                                                        )
                                                    );
                                                } else {
                                                    return false;
                                                }
                                            } else {
                                                return false;
                                            }
                                        }
                                    );
                                } else {
                                    return produit.criteres.some(
                                        (produitCritere) => {
                                            const valeurIdExists =
                                                produitCritere.hasOwnProperty(
                                                    "valeur_id"
                                                );
                                            if (
                                                valeurIdExists &&
                                                valeurIdExists !== null &&
                                                numericCritereId ===
                                                    produitCritere.critere_id
                                            ) {
                                                return (
                                                    produitCritere.valeur_id ===
                                                    critereInArray.id
                                                );
                                            } else {
                                                return false;
                                            }
                                        }
                                    );
                                }
                            });
                        } else {
                            return produit.criteres.some((produitCritere) => {
                                const valeurIdExists =
                                    produitCritere.hasOwnProperty("valeur_id");
                                if (
                                    valeurIdExists &&
                                    valeurIdExists !== null &&
                                    numericCritereId ===
                                        produitCritere.critere_id
                                ) {
                                    if (
                                        produitCritere.valeur_id ===
                                            selectedCritere.id ||
                                        (!!produitCritere.critere_valeur &&
                                            !!produitCritere.critere_valeur
                                                .inclus_all === true)
                                    ) {
                                        return (
                                            (valeurIdExists &&
                                                produitCritere.valeur_id ===
                                                    selectedCritere.id) ||
                                            (produitCritere.critere_valeur &&
                                                produitCritere.critere_valeur
                                                    .inclus_all === true)
                                        );
                                    } else if (
                                        typeof selectedCritere === "object" &&
                                        selectedCritere !== null &&
                                        numericCritereId ===
                                            produitCritere.critere_id
                                    ) {
                                        // HORAIRE
                                        if (
                                            "hours" in selectedCritere &&
                                            "minutes" in selectedCritere
                                        ) {
                                            const timeSelectedCritere = `${String(
                                                selectedCritere.hours
                                            ).padStart(2, "0")}h${String(
                                                selectedCritere.minutes
                                            ).padStart(2, "0")}`;
                                            return (
                                                produitCritere.valeur ===
                                                timeSelectedCritere
                                            );
                                        } else if (
                                            selectedCritere instanceof Date &&
                                            numericCritereId ===
                                                produitCritere.critere_id
                                        ) {
                                            // DATE
                                            if (
                                                produitCritere.critere
                                                    .type_champ_form === "date"
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
                                                } else {
                                                    return false;
                                                }
                                            } else {
                                                return false;
                                            }
                                        } else {
                                            return false;
                                        }
                                    } else if (
                                        produitCritere.critere
                                            .type_champ_form === "range" ||
                                        produitCritere.critere
                                            .type_champ_form === "rayon"
                                    ) {
                                        const minValue = 0;
                                        const maxValue = produitCritere.valeur;

                                        if (
                                            !isNaN(minValue) &&
                                            !isNaN(maxValue)
                                        ) {
                                            return (
                                                selectedCritere <= maxValue &&
                                                selectedCritere >= minValue
                                            );
                                        } else {
                                            return false;
                                        }
                                    } else if (
                                        typeof selectedCritere === "number" &&
                                        selectedCritere !== null
                                    ) {
                                        // NUMBER
                                        const prodValeurAsNumber = parseFloat(
                                            produitCritere.valeur
                                        );
                                        if (!isNaN(prodValeurAsNumber)) {
                                            return (
                                                numericCritereId ===
                                                    produitCritere.critere_id &&
                                                prodValeurAsNumber ===
                                                    selectedCritere
                                            );
                                        } else {
                                            return false;
                                        }
                                    } else if (
                                        typeof selectedCritere === "string" &&
                                        selectedCritere !== null
                                    ) {
                                        if (selectedCritere.trim() === "") {
                                            return true;
                                        } else {
                                            return (
                                                numericCritereId ===
                                                    produitCritere.critere_id &&
                                                produitCritere.valeur !==
                                                    null &&
                                                produitCritere.valeur ===
                                                    selectedCritere
                                            );
                                        }
                                    } else {
                                        return false;
                                    }
                                } else {
                                    return false;
                                }
                            });
                        }
                    }) &&
                    selectedSousCriteres.value.every(
                        (selectedSousCritereEntry) => {
                            const [sousCritereId, selectedSousCritere] =
                                selectedSousCritereEntry;
                            const numericSousCritereId =
                                parseInt(sousCritereId);
                            return produit.criteres.some((produitCritere) => {
                                return (
                                    produitCritere.sous_criteres &&
                                    produitCritere.sous_criteres.some(
                                        (sousCritere) => {
                                            // console.log(sousCritere, selectedSousCritere);
                                            if (
                                                sousCritere.sous_critere
                                                    .type_champ_form ===
                                                    "range" &&
                                                selectedSousCritere !== null &&
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
                                                selectedSousCritere !== null &&
                                                numericSousCritereId ===
                                                    sousCritere.sous_critere_id
                                            ) {
                                                const prodValeurAsNumber =
                                                    parseFloat(
                                                        sousCritere.valeur
                                                    );
                                                if (
                                                    !isNaN(prodValeurAsNumber)
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
                                                selectedSousCritere !== null
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
                            });
                        }
                    )
                );
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
