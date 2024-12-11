<?php

namespace r3pt1s\betternpc\entity\model;

final readonly class SkinModel {

    public function __construct(
        private string $id,
        private string $directoryPath
    ) {}

    public function getSkinId(): ?string {
        if (!$this->isValid()) return null;
        if (($read = @file_get_contents($this->directoryPath . "/" . $this->id . "_skin_id.dat")) !== false) return $read;
        return null;
    }

    public function getSkinData(): ?string {
        if (!$this->isValid()) return null;
        if (($read = @file_get_contents($this->directoryPath . "/" . $this->id . "_skin_data.dat")) !== false) return $read;
        return null;
    }

    public function getCapeData(): ?string {
        if (!$this->isValid()) return null;
        if (($read = @file_get_contents($this->directoryPath . "/" . $this->id . "_cape_data.dat")) !== false) return $read;
        return null;
    }

    public function getGeoName(): ?string {
        if (!$this->isValid()) return null;
        if (($read = @file_get_contents($this->directoryPath . "/" . $this->id . "_geo_name.dat")) !== false) return $read;
        return null;
    }

    public function getGeoData(): ?string {
        if (!$this->isValid()) return null;
        if (($read = @file_get_contents($this->directoryPath . "/" . $this->id . "_geo_data.dat")) !== false) return $read;
        return null;
    }

    public function isValid(): bool {
        return @file_exists($this->directoryPath) &&
            @file_exists($this->directoryPath . "/" . $this->id . "_skin_id.dat") &&
            @file_exists($this->directoryPath . "/" . $this->id . "_skin_data.dat");
    }

    public function getId(): string {
        return $this->id;
    }

    public function getDirectoryPath(): string {
        return $this->directoryPath;
    }
}