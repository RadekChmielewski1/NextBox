function openLocker(lockerNumber) {
    const lockerID = lockerNumber;
    switch (lockerNumber) {
        case 1:
            axios.get("http://raspberry.local:25565/openLockerSmall");
            break;
        case 2:
            axios.get("http://raspberry.local:25565/openLockerMedium");
            break;
        case 3:
            axios.get("http://raspberry.local:25565/openLockerLarge");
            break;
    }
}